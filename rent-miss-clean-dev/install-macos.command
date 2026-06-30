#!/bin/bash

APP_NAME="Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ"
APP_DIR_NAME="rent-miss-clean"
DB_NAME="if0_41710498_rent"
PORT=8080

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
INSTALL_DIR="$HOME/Library/Application Support/$APP_DIR_NAME"

echo ""
echo "============================================"
echo "  $APP_NAME Installer for macOS"
echo "============================================"
echo ""

# ── Step 1: Copy files ──────────────────────────────────────────────────
echo -e "${YELLOW}[1/4]${NC} Installing application files..."
mkdir -p "$INSTALL_DIR"
rsync -a --exclude='.git' --exclude='node_modules' --exclude='installer' --exclude='build-installer.php' --exclude='*.command' "$SCRIPT_DIR/" "$INSTALL_DIR/"
echo -e "  ${GREEN}✓${NC} Files installed to $INSTALL_DIR"

# ── Step 2: Find MySQL & detect password ────────────────────────────────
echo -e "${YELLOW}[2/4]${NC} Detecting MySQL..."

MYSQL_CMD=""
DB_PASSWORD=""

# Check for MySQL/MariaDB in common locations
for candidate in \
    "$(which mysql 2>/dev/null)" \
    "/usr/local/mysql/bin/mysql" \
    "/opt/homebrew/opt/mysql/bin/mysql" \
    "/opt/homebrew/opt/mariadb/bin/mysql" \
    "/Applications/MAMP/Library/bin/mysql" \
    "/Applications/XAMPP/xamppfiles/bin/mysql"; do
    if [ -x "$candidate" ]; then
        MYSQL_CMD="$candidate"
        break
    fi
done

if [ -n "$MYSQL_CMD" ]; then
    echo -e "  Found MySQL at: ${GREEN}$MYSQL_CMD${NC}"

    # If connection fails, try connecting via Homebrew MySQL first (it's likely running)
    if ! "$MYSQL_CMD" -u root -e "SELECT 1" >/dev/null 2>&1; then
        echo -e "  ${YELLOW}Checking Homebrew MySQL...${NC}"
        if [ -x "/opt/homebrew/opt/mysql/bin/mysql" ] && "/opt/homebrew/opt/mysql/bin/mysql" -u root -e "SELECT 1" >/dev/null 2>&1; then
            MYSQL_CMD="/opt/homebrew/opt/mysql/bin/mysql"
            echo -e "  Using Homebrew MySQL at: ${GREEN}$MYSQL_CMD${NC}"
        elif [ -x "/usr/local/mysql/bin/mysql" ] && "/usr/local/mysql/bin/mysql" -u root -e "SELECT 1" >/dev/null 2>&1; then
            MYSQL_CMD="/usr/local/mysql/bin/mysql"
            echo -e "  Using MySQL at: ${GREEN}$MYSQL_CMD${NC}"
        else
            echo -e "  ${YELLOW}Starting MySQL...${NC}"
            # Try to start the detected MySQL server
            if echo "$MYSQL_CMD" | grep -q "XAMPP"; then
                XAMPP_MYSQL_SERVER=$(dirname "$MYSQL_CMD")/mysql.server
                [ -f "$XAMPP_MYSQL_SERVER" ] && "$XAMPP_MYSQL_SERVER" start >/dev/null 2>&1 && sleep 3
            else
                MYSQLD=$(dirname "$MYSQL_CMD")/mysqld_safe
                [ -f "$MYSQLD" ] && "$MYSQLD" --skip-grant-tables >/dev/null 2>&1 && sleep 3
                if command -v brew &>/dev/null; then
                    brew services start mysql 2>/dev/null || brew services start mariadb 2>/dev/null || true
                    sleep 2
                fi
            fi
            sleep 2
        fi
    fi

    # Try common passwords
    for pw in "" "Admin123" "root" "password" "mysql"; do
        if "$MYSQL_CMD" -u root ${pw:+-p"$pw"} -e "SELECT 1" >/dev/null 2>&1; then
            DB_PASSWORD="$pw"
            echo -e "  ${GREEN}✓${NC} Connected (password: ${pw:+****})$([ -z "$pw" ] && echo 'empty')"
            break
        fi
    done
fi

if [ -z "$MYSQL_CMD" ]; then
    echo -e "  ${YELLOW}⚠ MySQL not found.${NC}"
    echo -e "  Install: brew install mariadb"
    echo -e "  Or start XAMPP MySQL from XAMPP Control Panel"
fi

# ── Step 3: Configure .env ──────────────────────────────────────────────
echo -e "${YELLOW}[3/4]${NC} Configuring for offline mode..."
cat > "$INSTALL_DIR/.env" << EOF
# Application Configuration
APP_NAME="$APP_NAME"
APP_ENV=offline
APP_URL=http://localhost:$PORT
APP_DEBUG=false

# Database Configuration
DB_HOST=localhost
DB_USERNAME=root
DB_PASSWORD=${DB_PASSWORD}
DB_DATABASE=$DB_NAME

# Production Database Configuration
PROD_DB_HOSTNAME=localhost
PROD_DB_USERNAME=root
PROD_DB_PASSWORD=${DB_PASSWORD}
PROD_DB_DATABASE=$DB_NAME

# UI Configuration
FONT_FAMILY="'Noto Sans Lao', sans-serif"

# Security
APP_KEY=

# ImageKit (empty for offline)
IMAGEKIT_PUBLIC_KEY=
IMAGEKIT_PRIVATE_KEY=
IMAGEKIT_URL_ENDPOINT=
EOF
echo -e "  ${GREEN}✓${NC} .env configured"

# ── Step 4: Import Database if connected ─────────────────────────────────
if [ -n "$MYSQL_CMD" ]; then
    echo -e "${YELLOW}[4/4]${NC} Importing database..."
    
    if "$MYSQL_CMD" -u root ${DB_PASSWORD:+-p"$DB_PASSWORD"} -e "SELECT 1" >/dev/null 2>&1; then
        "$MYSQL_CMD" -u root ${DB_PASSWORD:+-p"$DB_PASSWORD"} -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
        if [ -f "$INSTALL_DIR/if0_41710498_rent.sql" ]; then
            "$MYSQL_CMD" -u root ${DB_PASSWORD:+-p"$DB_PASSWORD"} "$DB_NAME" < "$INSTALL_DIR/if0_41710498_rent.sql"
            echo -e "  ${GREEN}✓${NC} Database imported successfully"
        fi
    else
        echo -e "  ${YELLOW}⚠${NC} Could not connect. Import manually:"
        echo -e "  mysql -u root -p $DB_NAME < $INSTALL_DIR/if0_41710498_rent.sql"
    fi
fi

# ── Step 5: Install XAMPP LaunchDaemons (auto-start on boot) ─────────
echo -e "${YELLOW}[5/5]${NC} Configuring XAMPP auto-start on boot..."

XAMPP_DIR="/Applications/XAMPP/xamppfiles"
if [ -d "$XAMPP_DIR" ]; then
    echo -e "  ${YELLOW}XAMPP detected. Setting up LaunchDaemons...${NC}"

    PLIST_DIR="/Library/LaunchDaemons"
    SVC_SCRIPT="$XAMPP_DIR/xampp"

    ADMIN_PASS=$(osascript -e 'Tell application "System Events" to display dialog "Enter your Mac password to configure XAMPP auto-start on boot:" default answer "" with hidden answer with icon caution with title "Miss Clean Installer"' -e 'text returned of result' 2>/dev/null)

    if [ -n "$ADMIN_PASS" ]; then
        write_plist() {
            local label="$1"
            local svc="$2"
            local file="$PLIST_DIR/$label.plist"
            echo "$ADMIN_PASS" | sudo -S bash -c "cat > '$file' << 'PLISTEOF'
<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<!DOCTYPE plist PUBLIC \"-//Apple//DTD PLIST 1.0//EN\" \"http://www.apple.com/DTDs/PropertyList-1.0.dtd\">
<plist version=\"1.0\">
<dict>
    <key>EnableTransactions</key>
    <true/>
    <key>Label</key>
    <string>$label</string>
    <key>ProgramArguments</key>
    <array>
        <string>$SVC_SCRIPT</string>
        <string>$svc</string>
    </array>
    <key>RunAtLoad</key>
    <true/>
    <key>WorkingDirectory</key>
    <string>$XAMPP_DIR</string>
    <key>KeepAlive</key>
    <false/>
    <key>AbandonProcessGroup</key>
    <true/>
</dict>
</plist>
PLISTEOF"
            echo "$ADMIN_PASS" | sudo -S chmod 644 "$file" 2>/dev/null
            echo "$ADMIN_PASS" | sudo -S chown root:wheel "$file" 2>/dev/null
            echo "$ADMIN_PASS" | sudo -S launchctl load -w "$file" 2>/dev/null
        }

        write_plist "apachefriends.xampp.apache.start" "startapache"
        write_plist "apachefriends.xampp.mysql.start" "startmysql"
        if [ -f "$XAMPP_DIR/etc/proftpd.conf" ]; then
            write_plist "apachefriends.xampp.proftpd.start" "startproftp"
        fi

        echo "$ADMIN_PASS" | sudo -S "$SVC_SCRIPT" startapache 2>/dev/null
        echo "$ADMIN_PASS" | sudo -S "$SVC_SCRIPT" startmysql 2>/dev/null
        echo -e "  ${GREEN}✓${NC} XAMPP services set to auto-start on boot"
    else
        echo -e "  ${YELLOW}⚠${NC} Skipped (no password). XAMPP won't auto-start on boot."
        echo -e "  See: https://gist.github.com/ozgrozer/aa3d0fe4d8c9ae8b04a3622f2e55fc04"
    fi
else
    echo -e "  ${YELLOW}⚠ XAMPP not found. Skipping auto-start setup.${NC}"
fi

# ── Done ─────────────────────────────────────────────────────────────────
echo ""
echo "============================================"
echo -e "  ${GREEN}Installation Complete!${NC}"
echo "============================================"
echo ""
echo "  App:  http://localhost:$PORT"
echo "  Login: admin / Admin123"
echo ""
echo "  XAMPP services (Apache, MySQL, ProFTPD) will auto-start on boot."
echo ""
echo ""
echo "============================================"
echo -e "  ${GREEN}Installation Complete!${NC}"
echo "============================================"
echo ""
echo "  To start the app:"
echo "    cd $INSTALL_DIR"
echo "    php -S localhost:$PORT router.php"
echo ""
echo "  Login: admin / Admin123"
echo ""
echo "  Make sure MySQL/MariaDB is running for database access."
echo ""

cd "$INSTALL_DIR"
php -S localhost:$PORT router.php &
PHP_PID=$!
sleep 1
open "http://localhost:$PORT"
wait $PHP_PID
