#!/bin/bash
set -e

MYSQL_ROOT_PASSWORD="${MYSQL_ROOT_PASSWORD:-Admin123}"
MYSQL_DATABASE="${MYSQL_DATABASE:-if0_42353445_thiengtham}"
MYSQL_DATA_DIR="/data/mysql"
MYSQL_RUN_DIR="/var/run/mysqld"
DB_SCHEMA="/var/www/html/database.sql"
MYSQL_CHARSET="--default-character-set=utf8mb4"
MYSQL_SOCK="$MYSQL_RUN_DIR/mysqld.sock"

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
# 2. Start MySQL normally (unix_socket auth for root@localhost)
# ============================================================
echo "[start.sh] Starting MySQL..."

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

if ! start_mysql ""; then
    echo "[start.sh] Normal start failed. Trying InnoDB recovery..."
    kill $MYSQL_PID 2>/dev/null || true; sleep 2
    rm -rf "$MYSQL_DATA_DIR/$MYSQL_DATABASE" 2>/dev/null || true
    rm -f "$MYSQL_DATA_DIR"/ibdata1 "$MYSQL_DATA_DIR"/ib_logfile* "$MYSQL_DATA_DIR"/undo00* 2>/dev/null || true
    if ! start_mysql ""; then
        echo "[start.sh] InnoDB recovery failed. Full wipe..."
        kill $MYSQL_PID 2>/dev/null || true; sleep 2
        rm -rf "$MYSQL_DATA_DIR" 2>/dev/null || true
        mkdir -p "$MYSQL_DATA_DIR"
        chown -R mysql:mysql "$MYSQL_DATA_DIR"
        mysql_install_db --user=mysql --datadir="$MYSQL_DATA_DIR" >/dev/null 2>&1
        start_mysql "" || { echo "[start.sh] FATAL: Cannot start MySQL."; exit 1; }
    fi
fi

# ============================================================
# 3. Fix TCP auth: ONE session via unix_socket
#
#    Connect as root via unix_socket (OS root → no password).
#    Compute password hash IN SQL using SHA1(SHA1('password')).
#    Use UPDATE mysql.global_priv to set plugin + hash directly.
#    Use INSERT for root@127.0.0.1 and root@%.
#    FLUSH PRIVILEGES at end of session.
#
#    After FLUSH, current session stays alive (existing connections
#    are not terminated). New connections use the updated auth.
# ============================================================
echo "[start.sh] Fixing TCP auth (unix_socket session, hash computed by MariaDB)..."

mysql --socket="$MYSQL_SOCK" -u root $MYSQL_CHARSET <<EOSQL
-- Compute mysql_native_password hash: *UPPER(HEX(SHA1(SHA1('password'))))
SET @hash = CONCAT('*', UPPER(HEX(SHA1(SHA1('${MYSQL_ROOT_PASSWORD}')))));
SELECT @hash AS computed_hash;

-- Fix root@localhost: switch plugin from unix_socket to mysql_native_password
UPDATE mysql.global_priv
SET priv = JSON_SET(priv, '$.plugin', 'mysql_native_password', '$.authentication_string', @hash)
WHERE User = 'root' AND Host = 'localhost';

-- Fix root@127.0.0.1: DELETE + INSERT with correct plugin and hash
DELETE FROM mysql.global_priv WHERE User = 'root' AND Host = '127.0.0.1';
INSERT INTO mysql.global_priv (User, Host, priv)
VALUES ('root', '127.0.0.1', CONCAT(
    '{"plugin":"mysql_native_password","authentication_string":"', @hash, '",',
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

-- Fix root@%: DELETE + INSERT with correct plugin and hash
DELETE FROM mysql.global_priv WHERE User = 'root' AND Host = '%';
INSERT INTO mysql.global_priv (User, Host, priv)
VALUES ('root', '%', CONCAT(
    '{"plugin":"mysql_native_password","authentication_string":"', @hash, '",',
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

-- Verify: show all root users and their plugins
SELECT User, Host, JSON_UNQUOTE(JSON_EXTRACT(priv, '$.plugin')) AS plugin,
       JSON_UNQUOTE(JSON_EXTRACT(priv, '$.authentication_string')) AS auth_str
FROM mysql.global_priv WHERE User = 'root';

-- Create the application database
CREATE DATABASE IF NOT EXISTS \`${MYSQL_DATABASE}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Reload grant tables (existing connections stay alive)
FLUSH PRIVILEGES;
EOSQL

echo "[start.sh] Auth fix complete."

# ============================================================
# 4. Verify TCP connection works
# ============================================================
echo "[start.sh] Verifying TCP connection..."

if mysql -h 127.0.0.1 -P 3306 -u root -p"$MYSQL_ROOT_PASSWORD" $MYSQL_CHARSET -e "SELECT 1" >/dev/null 2>&1; then
    echo "[start.sh] TCP auth: OK"
else
    echo "[start.sh] ERROR: TCP auth FAILED"
fi

if mysql --socket="$MYSQL_SOCK" -u root -p"$MYSQL_ROOT_PASSWORD" $MYSQL_CHARSET -e "SELECT 1" >/dev/null 2>&1; then
    echo "[start.sh] Socket auth: OK"
else
    echo "[start.sh] ERROR: Socket auth FAILED"
fi

echo "[start.sh] Database '${MYSQL_DATABASE}' ready."

# ============================================================
# 5. Remove stray directories in the MySQL data dir
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
        if ! mysql -h 127.0.0.1 -P 3306 -u root -p"$MYSQL_ROOT_PASSWORD" $MYSQL_CHARSET "$MYSQL_DATABASE" \
            -e "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='$MYSQL_DATABASE' AND TABLE_NAME='$name' LIMIT 1" >/dev/null 2>&1; then
            echo "[start.sh] Removing orphan data-dir: $name"
            rm -rf "$entry"
        fi
    done
fi

# ============================================================
# 6. Import database schema (idempotent)
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
    mysql -h 127.0.0.1 -P 3306 -u root -p"$MYSQL_ROOT_PASSWORD" $MYSQL_CHARSET "$MYSQL_DATABASE" < "$DB_SCHEMA" \
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
# 7. Run db_migrate.php for schema drift detection
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
# 8. Setup file storage: /data/uploads/ on HuggingFace bucket
# ============================================================
mkdir -p /data/uploads/pos-stock/products
mkdir -p /data/uploads/pos-stock/bill
chown -R www-data:www-data /data/uploads
echo "[start.sh] File storage directory /data/uploads/ ready."

# ============================================================
# 9. Configure Apache: port + Alias for /data/uploads
# ============================================================
sed -i "s/80/${PORT:-7860}/g" /etc/apache2/sites-available/000-default.conf
sed -i "s/80/${PORT:-7860}/g" /etc/apache2/ports.conf

cat > /etc/apache2/conf-available/uploads-alias.conf <<'ALIAS'
Alias /uploads /data/uploads
<Directory /data/uploads>
    Options +FollowSymLinks
    AllowOverride None
    Require all granted
</Directory>
ALIAS
a2enconf uploads-alias >/dev/null 2>&1
echo "[start.sh] Apache configured on port ${PORT:-7860} with /uploads alias."

# ============================================================
# 10. Generate .env file for PHP
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
# 11. Start Apache (foreground)
# ============================================================
echo "[start.sh] Starting Apache..."
apache2-foreground
