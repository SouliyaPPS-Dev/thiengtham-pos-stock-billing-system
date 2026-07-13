#!/bin/bash
set -e

MYSQL_ROOT_PASSWORD="${MYSQL_ROOT_PASSWORD:-root}"
MYSQL_DATABASE="${MYSQL_DATABASE:-if0_42353445_thiengtham}"
MYSQL_DATA_DIR="/data/mysql"
MYSQL_RUN_DIR="/var/run/mysqld"
DB_SCHEMA="/var/www/html/database.sql"
MYSQL_CHARSET="--default-character-set=utf8mb4"
MYSQL_SOCK="$MYSQL_RUN_DIR/mysqld.sock"

# Compute mysql_native_password hash: *UPPER(SHA1(SHA1(password)))
# This is what MariaDB uses internally for mysql_native_password auth.
compute_native_hash() {
    local pass="$1"
    local s1
    s1=$(printf '%s' "$pass" | sha1sum | awk '{print $1}')
    local s2
    s2=$(printf '%s' "$s1" | sha1sum | awk '{print toupper($0)}')
    echo "*${s2}"
}

MYSQL_NATIVE_HASH=$(compute_native_hash "$MYSQL_ROOT_PASSWORD")

# ============================================================
# 1. Initialize MySQL data directory if not already initialized
# ============================================================
if [ -d "$MYSQL_DATA_DIR/#binlog_cache_files" ]; then
    echo "[start.sh] Detected corrupted MariaDB binlog cache dir. Wiping data directory..."
    rm -rf "$MYSQL_DATA_DIR" 2>/dev/null || true
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
# 2. Start MySQL with --skip-grant-tables
#    Bypasses ALL auth. We fix everything, then restart normally.
# ============================================================
echo "[start.sh] Starting MySQL (skip-grant-tables for auth fix)..."

start_mysql() {
    local EXTRA_ARGS="$1"
    mysqld --user=mysql --datadir="$MYSQL_DATA_DIR" --socket="$MYSQL_SOCK" \
           --pid-file="$MYSQL_RUN_DIR/mysqld.pid" --port=3306 $EXTRA_ARGS &
    MYSQL_PID=$!
    for i in $(seq 1 30); do
        if mysqladmin ping --socket="$MYSQL_SOCK" --silent 2>/dev/null; then
            echo "[start.sh] MySQL is ready."
            return 0
        fi
        sleep 1
    done
    echo "[start.sh] ERROR: MySQL failed to start."
    return 1
}

kill $(cat "$MYSQL_RUN_DIR/mysqld.pid" 2>/dev/null) 2>/dev/null || true
sleep 2

# Always start with skip-grant-tables for the auth fix
start_mysql "--skip-grant-tables" || { echo "[start.sh] FATAL: Cannot start MySQL for auth fix."; exit 1; }

# ============================================================
# 3. Fix auth: switch ALL root users to mysql_native_password
#
#    MariaDB 10.4+ defaults to unix_socket which blocks TCP.
#    We use UPDATE mysql.global_priv to set the plugin AND hash
#    directly, because:
#    - SET PASSWORD requires a 41-digit hex hash (not plaintext)
#    - ALTER USER ... IDENTIFIED BY uses default plugin (unix_socket)
#    - PASSWORD() function removed in MariaDB 10.11+
#
#    We compute the mysql_native_password hash in bash and embed it.
# ============================================================
MYSQL_CMD="mysql --socket=$MYSQL_SOCK -u root"

echo "[start.sh] Fixing auth: setting mysql_native_password for all root users..."
echo "[start.sh] Password hash: ${MYSQL_NATIVE_HASH}"

$MYSQL_CMD $MYSQL_CHARSET <<EOSQL
FLUSH PRIVILEGES;

-- Fix root@localhost: switch plugin to mysql_native_password and set hash
UPDATE mysql.global_priv
SET priv = json_set(
    priv,
    '$.plugin', 'mysql_native_password',
    '$.authentication_string', '${MYSQL_NATIVE_HASH}'
)
WHERE User = 'root' AND Host = 'localhost';

-- Create root@127.0.0.1 if not exists (TCP connections use this)
DELETE FROM mysql.global_priv WHERE User = 'root' AND Host = '127.0.0.1';
INSERT IGNORE INTO mysql.global_priv (User, Host, priv)
VALUES ('root', '127.0.0.1', CONCAT(
    '{"plugin":"mysql_native_password","authentication_string":"${MYSQL_NATIVE_HASH}",',
    '"select_priv":"Y","insert_priv":"Y","update_priv":"Y","delete_priv":"Y",',
    '"create_priv":"Y","drop_priv":"Y","reload_priv":"Y","process_priv":"Y",',
    '"file_priv":"Y","grant_priv":"Y","references_priv":"Y","index_priv":"Y",',
    '"alter_priv":"Y","show_db_priv":"Y","super_priv":"Y",',
    '"create_tmp_table_priv":"Y","lock_tables_priv":"Y","execute_priv":"Y",',
    '"repl_slave_priv":"Y","repl_client_priv":"Y","create_view_priv":"Y",',
    '"show_view_priv":"Y","create_routine_priv":"Y","alter_routine_priv":"Y",',
    '"create_user_priv":"Y","event_priv":"Y","trigger_priv":"Y",',
    '"create_tablespace_priv":"Y"}'
));

-- Create root@% if not exists (remote connections)
DELETE FROM mysql.global_priv WHERE User = 'root' AND Host = '%';
INSERT IGNORE INTO mysql.global_priv (User, Host, priv)
VALUES ('root', '%', CONCAT(
    '{"plugin":"mysql_native_password","authentication_string":"${MYSQL_NATIVE_HASH}",',
    '"select_priv":"Y","insert_priv":"Y","update_priv":"Y","delete_priv":"Y",',
    '"create_priv":"Y","drop_priv":"Y","reload_priv":"Y","process_priv":"Y",',
    '"file_priv":"Y","grant_priv":"Y","references_priv":"Y","index_priv":"Y",',
    '"alter_priv":"Y","show_db_priv":"Y","super_priv":"Y",',
    '"create_tmp_table_priv":"Y","lock_tables_priv":"Y","execute_priv":"Y",',
    '"repl_slave_priv":"Y","repl_client_priv":"Y","create_view_priv":"Y",',
    '"show_view_priv":"Y","create_routine_priv":"Y","alter_routine_priv":"Y",',
    '"create_user_priv":"Y","event_priv":"Y","trigger_priv":"Y",',
    '"create_tablespace_priv":"Y"}'
));

-- Create the application database
CREATE DATABASE IF NOT EXISTS \`${MYSQL_DATABASE}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

FLUSH PRIVILEGES;
EOSQL

echo "[start.sh] Auth fixed. Restarting MySQL normally..."

# ============================================================
# 4. Restart MySQL normally (with auth enabled)
# ============================================================
kill $MYSQL_PID 2>/dev/null || true; sleep 2
start_mysql "" || { echo "[start.sh] FATAL: Cannot restart MySQL after auth fix."; exit 1; }

# ============================================================
# 5. Verify connection works (socket + TCP)
# ============================================================
if $MYSQL_CMD -p"$MYSQL_ROOT_PASSWORD" -e "SELECT 1" >/dev/null 2>&1; then
    echo "[start.sh] Socket auth verified."
else
    echo "[start.sh] ERROR: Socket auth failed after fix!"
fi

if mysql -h 127.0.0.1 -P 3306 -u root -p"$MYSQL_ROOT_PASSWORD" $MYSQL_CHARSET -e "SELECT 1" >/dev/null 2>&1; then
    echo "[start.sh] TCP auth verified."
else
    echo "[start.sh] ERROR: TCP auth failed after fix!"
fi

echo "[start.sh] Database '${MYSQL_DATABASE}' ready."

# ============================================================
# 6. Remove stray directories in the MySQL data dir
# ============================================================
DB_DATADIR="$MYSQL_DATA_DIR/$MYSQL_DATABASE"
if [ -d "$DB_DATADIR" ]; then
    find "$DB_DATADIR" -maxdepth 1 -type d -name '#sql-*' -exec rm -rf {} + 2>/dev/null && \
        echo "[start.sh] Cleaned #sql-* temp directories" || true

    for entry in "$DB_DATADIR"/*/; do
        [ -d "$entry" ] || continue
        name="$(basename "$entry")"
        case "$name" in
            mysql|performance_schema|information_schema|sys) continue ;;
        esac
        if [ -f "$DB_DATADIR/$name.frm" ] || [ -f "$DB_DATADIR/$name.ibd" ]; then
            echo "[start.sh] Removing stray table directory: $name"
            rm -rf "$entry"
        fi
    done

    for entry in "$DB_DATADIR"/*/; do
        [ -d "$entry" ] || continue
        name="$(basename "$entry")"
        case "$name" in
            mysql|performance_schema|information_schema|sys) continue ;;
        esac
        if ! $MYSQL_CMD -p"$MYSQL_ROOT_PASSWORD" $MYSQL_CHARSET "$MYSQL_DATABASE" \
            -e "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='$MYSQL_DATABASE' AND TABLE_NAME='$name' LIMIT 1" >/dev/null 2>&1; then
            echo "[start.sh] Removing orphan data-dir: $name"
            rm -rf "$entry"
        fi
    done
fi

# ============================================================
# 7. Import database schema (idempotent)
#    Priority: /data/database.sql (persistent) > /var/www/html/database.sql (repo)
# ============================================================
DB_SCHEMA_REPO="/var/www/html/database.sql"
DB_SCHEMA_BUCKET="/data/database.sql"

if [ -f "$DB_SCHEMA_REPO" ]; then
    if [ ! -f "$DB_SCHEMA_BUCKET" ] || ! diff -q "$DB_SCHEMA_REPO" "$DB_SCHEMA_BUCKET" >/dev/null 2>&1; then
        cp "$DB_SCHEMA_REPO" "$DB_SCHEMA_BUCKET"
        echo "[start.sh] Synced database.sql to /data/ (persistent storage)."
    fi
fi

if [ -f "$DB_SCHEMA_BUCKET" ]; then
    DB_SCHEMA="$DB_SCHEMA_BUCKET"
elif [ -f "$DB_SCHEMA_REPO" ]; then
    DB_SCHEMA="$DB_SCHEMA_REPO"
else
    DB_SCHEMA=""
fi

if [ -n "$DB_SCHEMA" ]; then
    echo "[start.sh] Importing database schema from $DB_SCHEMA (idempotent)..."
    $MYSQL_CMD -p"$MYSQL_ROOT_PASSWORD" $MYSQL_CHARSET "$MYSQL_DATABASE" < "$DB_SCHEMA" \
        && echo "[start.sh] Database schema imported successfully." \
        || echo "[start.sh] Database schema import had warnings (may be OK on existing data)."
else
    echo "[start.sh] WARNING: database.sql not found. Skipping schema import."
fi

# Clean stray dirs after import
if [ -d "$DB_DATADIR" ]; then
    find "$DB_DATADIR" -maxdepth 1 -type d -name '#sql-*' -exec rm -rf {} + 2>/dev/null && \
        echo "[start.sh] Post-import: cleaned #sql-* temp dirs" || true
    for entry in "$DB_DATADIR"/*/; do
        [ -d "$entry" ] || continue
        name="$(basename "$entry")"
        case "$name" in
            mysql|performance_schema|information_schema|sys) continue ;;
        esac
        if [ -f "$DB_DATADIR/$name.frm" ] || [ -f "$DB_DATADIR/$name.ibd" ]; then
            rm -rf "$entry"
        fi
    done
fi

# ============================================================
# 8. Run db_migrate.php for schema drift detection
# ============================================================
echo "[start.sh] Syncing bucket DB schema (idempotent)..."
DB_HOST=127.0.0.1 DB_USER=root DB_PASS="$MYSQL_ROOT_PASSWORD" DB_NAME="$MYSQL_DATABASE" \
    php /var/www/html/db_migrate.php \
    && echo "[start.sh] Bucket DB schema synced." \
    || echo "[start.sh] Bucket DB schema sync had non-fatal warnings."

# Final stray dir cleanup
if [ -d "$DB_DATADIR" ]; then
    find "$DB_DATADIR" -maxdepth 1 -type d -name '#sql-*' -exec rm -rf {} + 2>/dev/null || true
fi

# ============================================================
# 9. Generate .env file for PHP
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
# 10. Update Apache port
# ============================================================
sed -i "s/80/${PORT:-7860}/g" /etc/apache2/sites-available/000-default.conf
sed -i "s/80/${PORT:-7860}/g" /etc/apache2/ports.conf
echo "[start.sh] Apache configured on port ${PORT:-7860}."

# ============================================================
# 11. Start Apache (foreground)
# ============================================================
echo "[start.sh] Starting Apache..."
apache2-foreground
