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
# 4a. Remove stray per-table directories in the MySQL data dir.
#     InnoDB tables are stored as files (table.frm / table.ibd),
#     never as directories. A leftover directory named like a table
#     (e.g. `quotations/`, sometimes with nested junk inside) shadows
#     the real table and makes every ALTER/CREATE fail with
#     errno 21 "Is a directory". Remove ALL non-system directories
#     BEFORE schema import and migration so both can proceed.
# ============================================================
DB_DATADIR="$MYSQL_DATA_DIR/$MYSQL_DATABASE"
if [ -d "$DB_DATADIR" ]; then
    for entry in "$DB_DATADIR"/*/; do
        [ -d "$entry" ] || continue
        name="$(basename "$entry")"
        case "$name" in
            mysql|performance_schema|information_schema|sys) continue ;;
        esac
        if [ -f "$DB_DATADIR/$name.frm" ] || [ -f "$DB_DATADIR/$name.ibd" ]; then
            echo "[start.sh] Removing stray table directory (has sibling .frm/.ibd): $name"
            rm -rf "$entry"
        fi
    done
    for entry in "$DB_DATADIR"/*/; do
        [ -d "$entry" ] || continue
        name="$(basename "$entry")"
        case "$name" in
            mysql|performance_schema|information_schema|sys) continue ;;
        esac
        if ! $MYSQL_CMD -p"$MYSQL_ROOT_PASSWORD" $MYSQL_CHARSET "$MYSQL_DATABASE" -e "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='$MYSQL_DATABASE' AND TABLE_NAME='$name' LIMIT 1" >/dev/null 2>&1; then
            echo "[start.sh] Removing orphan data-dir (no such table): $name"
            rm -rf "$entry"
        fi
    done
fi

# ============================================================
# 4b. Import database schema (with correct UTF-8 charset)
#    Always re-import because database.sql is now fully idempotent
#    (CREATE DATABASE IF NOT EXISTS, CREATE TABLE IF NOT EXISTS,
#    INSERT IGNORE). This guarantees any missing columns added by
#    new schema versions are present even on existing bucket data.
#    Priority: bucket mount (/data/database.sql) > repo (/var/www/html/database.sql)
# ============================================================
DB_SCHEMA_REPO="/var/www/html/database.sql"
DB_SCHEMA_BUCKET="/data/database.sql"
if [ -f "$DB_SCHEMA_BUCKET" ]; then
    DB_SCHEMA="$DB_SCHEMA_BUCKET"
    echo "[start.sh] Using database.sql from bucket: $DB_SCHEMA_BUCKET"
elif [ -f "$DB_SCHEMA_REPO" ]; then
    DB_SCHEMA="$DB_SCHEMA_REPO"
    echo "[start.sh] Using database.sql from repo: $DB_SCHEMA_REPO"
else
    DB_SCHEMA=""
fi

if [ -n "$DB_SCHEMA" ]; then
    echo "[start.sh] Importing database schema from $DB_SCHEMA (idempotent)..."
    $MYSQL_CMD -p"$MYSQL_ROOT_PASSWORD" $MYSQL_CHARSET "$MYSQL_DATABASE" < "$DB_SCHEMA" \
        && echo "[start.sh] Database schema imported successfully." \
        || echo "[start.sh] Database schema import had warnings (may be OK on existing data)."
else
    echo "[start.sh] WARNING: database.sql not found in repo or bucket. Skipping schema import."
fi

# ============================================================
# 4c. Idempotent bucket-DB schema sync (robust PHP runner).
#     database.sql handles CREATE TABLE IF NOT EXISTS; db_migrate.php
#     handles adding any columns that were added in newer code versions.
#     Runs on EVERY startup to catch schema drift. PDO-based,
#     guarded + try/catch, so it never fails the container start.
# ============================================================
echo "[start.sh] Syncing bucket DB schema (idempotent)..."
DB_HOST=127.0.0.1 DB_USER=root DB_PASS="$MYSQL_ROOT_PASSWORD" DB_NAME="$MYSQL_DATABASE" \
    php /var/www/html/db_migrate.php \
    && echo "[start.sh] Bucket DB schema synced." \
    || echo "[start.sh] Bucket DB schema sync had non-fatal warnings."

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
