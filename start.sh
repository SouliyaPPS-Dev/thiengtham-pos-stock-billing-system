#!/bin/bash
set -e

MYSQL_ROOT_PASSWORD="${MYSQL_ROOT_PASSWORD:-root}"
MYSQL_DATABASE="${MYSQL_DATABASE:-if0_42353445_thiengtham}"
MYSQL_DATA_DIR="/data/mysql"
MYSQL_RUN_DIR="/var/run/mysqld"
DB_SCHEMA="/var/www/html/database.sql"
MYSQL_CHARSET="--default-character-set=utf8mb4"

# ============================================================
# 1. Initialize MySQL data directory if not already initialized
# ============================================================
if [ -d "$MYSQL_DATA_DIR/#binlog_cache_files" ]; then
    echo "[start.sh] Detected corrupted MariaDB binlog cache dir. Reinitializing data directory..."
    mv "$MYSQL_DATA_DIR" "${MYSQL_DATA_DIR}_corrupted_$$"
fi

if [ ! -d "$MYSQL_DATA_DIR/mysql" ]; then
    echo "[start.sh] Initializing MySQL data directory in $MYSQL_DATA_DIR ..."
    mkdir -p "$MYSQL_DATA_DIR"
    chown -R mysql:mysql "$MYSQL_DATA_DIR"
    mysql_install_db --user=mysql --datadir="$MYSQL_DATA_DIR"
    echo "[start.sh] MySQL data directory initialized."
fi

chown -R mysql:mysql "$MYSQL_DATA_DIR" "$MYSQL_RUN_DIR"

# ============================================================
# 2. Start MySQL (MariaDB)
# ============================================================
echo "[start.sh] Starting MySQL..."

# Try normal start first; if it fails, attempt InnoDB recovery
mysqld --user=mysql --datadir="$MYSQL_DATA_DIR" --socket="$MYSQL_RUN_DIR/mysqld.sock" \
       --pid-file="$MYSQL_RUN_DIR/mysqld.pid" \
       --port=3306 \
       --innodb-force-recovery=1 &

MYSQL_PID=$!

# Wait for MySQL to be ready
for i in $(seq 1 30); do
    if mysqladmin ping --socket="$MYSQL_RUN_DIR/mysqld.sock" --silent 2>/dev/null; then
        echo "[start.sh] MySQL is ready."
        break
    fi
    sleep 1
done

if ! mysqladmin ping --socket="$MYSQL_RUN_DIR/mysqld.sock" --silent 2>/dev/null; then
    echo "[start.sh] MySQL failed with InnoDB tablespace corruption. Resetting InnoDB..."
    kill $MYSQL_PID 2>/dev/null || true
    sleep 2
    # Remove application .ibd files and InnoDB system tablespace so everything gets recreated
    rm -rf "$MYSQL_DATA_DIR/$MYSQL_DATABASE" "$MYSQL_DATA_DIR/ib_logfile"* "$MYSQL_DATA_DIR/ibdata1" 2>/dev/null || true
    rm -f "$SCHEMA_VERSION_FILE" 2>/dev/null || true
    echo "[start.sh] Retrying MySQL startup with clean InnoDB..."
    mysqld --user=mysql --datadir="$MYSQL_DATA_DIR" --socket="$MYSQL_RUN_DIR/mysqld.sock" \
           --pid-file="$MYSQL_RUN_DIR/mysqld.pid" \
           --port=3306 &
    MYSQL_PID=$!
    for i in $(seq 1 30); do
        if mysqladmin ping --socket="$MYSQL_RUN_DIR/mysqld.sock" --silent 2>/dev/null; then
            echo "[start.sh] MySQL is ready after InnoDB reset."
            break
        fi
        sleep 1
    done
fi

if ! mysqladmin ping --socket="$MYSQL_RUN_DIR/mysqld.sock" --silent 2>/dev/null; then
    echo "[start.sh] ERROR: MySQL failed to start."
    exit 1
fi

# ============================================================
# 3. Set root password and create database
# ============================================================
MYSQL_CMD="mysql --socket=$MYSQL_RUN_DIR/mysqld.sock -u root"
# First try: connect with password (for restart after first setup)
if $MYSQL_CMD -p"$MYSQL_ROOT_PASSWORD" -e "SELECT 1" >/dev/null 2>&1; then
    echo "[start.sh] Root password already set. Ensuring database exists..."
    $MYSQL_CMD -p"$MYSQL_ROOT_PASSWORD" $MYSQL_CHARSET <<EOSQL
    CREATE DATABASE IF NOT EXISTS \`${MYSQL_DATABASE}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EOSQL
# Second try: connect without password (first run, unix_socket auth)
else
    echo "[start.sh] Setting up root password and users..."
    $MYSQL_CMD $MYSQL_CHARSET <<EOSQL
    ALTER USER 'root'@'localhost' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}';
    CREATE USER IF NOT EXISTS 'root'@'127.0.0.1' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}';
    GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION;
    CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}';
    GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;
    CREATE DATABASE IF NOT EXISTS \`${MYSQL_DATABASE}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    FLUSH PRIVILEGES;
EOSQL
fi

echo "[start.sh] Database '${MYSQL_DATABASE}' ready."

# ============================================================
# 4. Import database schema (with correct UTF-8 charset)
# ============================================================
SCHEMA_VERSION_FILE="/data/.schema_imported"

if [ -f "$DB_SCHEMA" ]; then
    # Force re-import if flag file is missing (e.g. first deploy or data corruption fix)
    if [ ! -f "$SCHEMA_VERSION_FILE" ]; then
        echo "[start.sh] Importing database schema from database.sql..."
        $MYSQL_CMD -p"$MYSQL_ROOT_PASSWORD" $MYSQL_CHARSET "$MYSQL_DATABASE" < "$DB_SCHEMA"
        date > "$SCHEMA_VERSION_FILE"
        echo "[start.sh] Database schema imported successfully."
    else
        echo "[start.sh] Database schema already imported. Skipping."
    fi
else
    echo "[start.sh] WARNING: database.sql not found. Skipping schema import."
fi

# ============================================================
# 4b. Apply incremental migrations (idempotent) so the bucket DB
#     stays in sync with code across redeploys. database.sql is
#     only imported once; this safely adds any missing columns/tables.
# ============================================================
MIGRATION_DIR="/var/www/html/database/migrations"
if [ -d "$MIGRATION_DIR" ]; then
    shopt -s nullglob
    for m in "$MIGRATION_DIR"/*.sql; do
        echo "[start.sh] Applying migration: $m"
        $MYSQL_CMD -p"$MYSQL_ROOT_PASSWORD" $MYSQL_CHARSET "$MYSQL_DATABASE" < "$m" \
            && echo "[start.sh]   -> done: $(basename "$m")" \
            || echo "[start.sh]   -> warnings (non-fatal): $(basename "$m")"
    done
    shopt -u nullglob
fi

# ============================================================
# 5. Generate .env file for PHP
# ============================================================
APP_URL="${PROD_APP_URL:-}"

cat > /var/www/html/.env <<-EOF
APP_NAME="POS & Stock"
APP_ENV=production
APP_ENV_HF=true
APP_URL=${APP_URL}
APP_DEBUG=${APP_DEBUG:-false}

PROD_APP_URL=${APP_URL}

DB_HOST=127.0.0.1
DB_USERNAME=root
DB_PASSWORD=${MYSQL_ROOT_PASSWORD}
DB_DATABASE=${MYSQL_DATABASE}

PROD_DB_HOSTNAME=127.0.0.1
PROD_DB_USERNAME=root
PROD_DB_PASSWORD=${MYSQL_ROOT_PASSWORD}
PROD_DB_DATABASE=${MYSQL_DATABASE}

FONT_FAMILY="'Noto Sans Lao', sans-serif"

IMAGEKIT_PUBLIC_KEY=${IMAGEKIT_PUBLIC_KEY:-}
IMAGEKIT_PRIVATE_KEY=${IMAGEKIT_PRIVATE_KEY:-}
IMAGEKIT_URL_ENDPOINT=${IMAGEKIT_URL_ENDPOINT:-}
EOF

echo "[start.sh] .env file generated."

# ============================================================
# 6. Update Apache port
# ============================================================
sed -i "s/80/${PORT:-7860}/g" /etc/apache2/sites-available/000-default.conf
sed -i "s/80/${PORT:-7860}/g" /etc/apache2/ports.conf
echo "[start.sh] Apache configured on port ${PORT:-7860}."

# ============================================================
# 7. Start Apache (foreground)
# ============================================================
echo "[start.sh] Starting Apache..."
apache2-foreground
