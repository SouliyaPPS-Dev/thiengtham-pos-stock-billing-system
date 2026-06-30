<?php
/**
 * Miss Clean Installer Generator
 * 
 * Usage: php build-installer.php
 * 
 * Generates:
 *   - Miss-Clean-Setup-{version}.exe  (Windows NSIS installer)
 *   - Miss-Clean-Setup-{version}.dmg  (macOS disk image)
 * 
 * Requirements (on build machine):
 *   - PHP 8.0+ with ZIP extension
 *   - NSIS 3+ (brew install nsis)  — for .exe
 *   - hdiutil (built into macOS)   — for .dmg
 */

// ─── Configuration ───────────────────────────────────────────────────────────

$PROJECT_ROOT = __DIR__;
$OUTPUT_DIR = $PROJECT_ROOT . '/installer';
$APP_DIR_NAME = 'rent-miss-clean';
$APP_NAME = 'Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ';
$APP_DISPLAY_NAME = 'Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ';
$APP_VERSION = '1.0.0';
$PUBLISHER = 'Miss Clean';
$MAC_PORT = 8080;

// Files/directories to EXCLUDE from the installer package
$EXCLUDE_PATTERNS = [
    '/^\.git/',
    '/^\.vscode/',
    '/^\.antigravitycli/',
    '/^node_modules/',
    '/^installer/',
    '/^\.env$/',
    '/\.DS_Store$/',
    '/build-installer\.php$/',
    '/docker-entrypoint\.sh$/',
    '/Dockerfile$/',
    '/README\.md$/',
    '/PROMPT_INIT\.md$/',
    '/package\.json$/',
    '/tailwind\.config\.js$/',
    '/backfill_invoice_numbers\.php$/',
    '/add_payment_status_db\.php$/',
    '/add_missing_columns\.php$/',
    '/add_customer_avatar\.php$/',
    '/add_staff_avatar\.php$/',
    '/fix_expenses_db\.php$/',
    '/force_fix_db\.php$/',
    '/test_db\.php$/',
    '/add_invoice_columns\.php$/',
    '/ບິນເຊົ່າເຄື່ອງເລກທີ INV-2605-S1-0001\.pdf$/',
    '/offline\.html$/',
    '/^if0_41710498_rent\.sql$/',
];

// ─── Helper Functions ────────────────────────────────────────────────────────

function shouldExclude(string $relativePath, array $patterns): bool {
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $relativePath)) return true;
    }
    return false;
}

function collectFiles(string $rootDir, array $excludePatterns): array {
    $files = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootDir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    foreach ($iterator as $file) {
        if (!$file->isFile()) continue;
        $relativePath = str_replace($rootDir . '/', '', $file->getPathname());
        if (shouldExclude($relativePath, $excludePatterns)) continue;
        $files[] = $relativePath;
    }
    sort($files);
    return $files;
}

function formatFileSize(int $bytes): string {
    if ($bytes >= 1048576) return number_format($bytes / 1048576, 1) . ' MB';
    if ($bytes >= 1024) return number_format($bytes / 1024, 1) . ' KB';
    return $bytes . ' B';
}

function rmrf(string $dir): void {
    if (!file_exists($dir)) return;
    if (is_link($dir) || !is_dir($dir)) {
        unlink($dir);
        return;
    }
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($files as $f) {
        $path = $f->getPathname();
        if (is_link($path)) {
            unlink($path);
        } elseif ($f->isDir()) {
            rmdir($path);
        } else {
            unlink($path);
        }
    }
    rmdir($dir);
}

function createIco(string $pngFile, string $icoFile): bool {
    $png = file_get_contents($pngFile);
    if ($png === false) return false;
    $pngLen = strlen($png);
    // ICO header: reserved(2) + type(2=1 for icon) + count(2)
    $ico = pack('vvv', 0, 1, 1);
    // Directory entry: w(1), h(1), colors(1), reserved(1), planes(2), bpp(2), size(4), offset(4)
    $im = getimagesize($pngFile);
    $w = $im[0] >= 256 ? 0 : $im[0];
    $h = $im[1] >= 256 ? 0 : $im[1];
    $ico .= pack('CCCCvvVV', $w, $h, 0, 0, 1, 32, $pngLen, 22);
    $ico .= $png;
    return file_put_contents($icoFile, $ico) !== false;
}

function cpdir(string $src, string $dst): void {
    if (!is_dir($dst)) mkdir($dst, 0755, true);
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($src, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($iterator as $item) {
        $target = $dst . '/' . $iterator->getSubPathname();
        if ($item->isDir()) {
            mkdir($target, 0755, true);
        } else {
            copy($item->getPathname(), $target);
        }
    }
}

// ─── Step 1: Output Directory ───────────────────────────────────────────────

echo "=== Miss Clean Multi-Platform Installer Generator ===\n\n";
echo "[1/6] Preparing output directory...\n";

if (is_dir($OUTPUT_DIR)) rmrf($OUTPUT_DIR);
mkdir($OUTPUT_DIR, 0755, true);

// ─── Step 2: Collect & Copy Files ───────────────────────────────────────────

echo "[2/6] Collecting project files...\n";

$files = collectFiles($PROJECT_ROOT, $EXCLUDE_PATTERNS);
$sqlFile = $PROJECT_ROOT . '/if0_41710498_rent.sql';
echo "  Found " . count($files) . " files\n";

echo "[3/6] Copying files to installer package...\n";

$targetDir = $OUTPUT_DIR . '/files';
foreach ($files as $relativePath) {
    $src = $PROJECT_ROOT . '/' . $relativePath;
    $dst = $targetDir . '/' . $relativePath;
    $dstDir = dirname($dst);
    if (!is_dir($dstDir)) mkdir($dstDir, 0755, true);
    copy($src, $dst);
}
if (file_exists($sqlFile)) {
    copy($sqlFile, $targetDir . '/if0_41710498_rent.sql');
}
echo "  Done.\n";

// ═══════════════════════════════════════════════════════════════════════════
// WINDOWS INSTALLER
// ═══════════════════════════════════════════════════════════════════════════

echo "[4/6] Generating Windows installer (.exe)...\n";

// ─── NSIS Script ────────────────────────────────────────────────────────────

$nsisDirs = [];
foreach ($files as $relativePath) {
    $destDir = dirname($relativePath);
    $subDir = ($destDir !== '.') ? $destDir : '';
    $nsisSrc = str_replace('/', '\\', 'files\\' . $relativePath);
    $nsisDirs[$subDir][] = $nsisSrc;
}

$nsisFileEntries = '';
foreach ($nsisDirs as $dir => $srcFiles) {
    $nsisPath = empty($dir)
        ? "\$INSTDIR\\{$APP_DIR_NAME}"
        : "\$INSTDIR\\{$APP_DIR_NAME}\\" . str_replace('/', '\\', $dir);
    $nsisFileEntries .= "  SetOutPath \"{$nsisPath}\"\n";
    foreach ($srcFiles as $src) {
        $nsisFileEntries .= "  File \"{$src}\"\n";
    }
}

$nsiContent = <<<NSIS
; NSIS Installer Script for Miss Clean
; Generated by build-installer.php

!define PRODUCT_NAME "{$APP_DISPLAY_NAME}"
!define PRODUCT_VERSION "{$APP_VERSION}"
!define PRODUCT_PUBLISHER "{$PUBLISHER}"
!define PRODUCT_DIR_REGKEY "Software\\Microsoft\\Windows\\CurrentVersion\\App Paths\\start-miss-clean.bat"
!define PRODUCT_UNINST_KEY "Software\\Microsoft\\Windows\\CurrentVersion\\Uninstall\\\${PRODUCT_NAME}"
!define PRODUCT_UNINST_ROOT_KEY "HKLM"

Name "\${PRODUCT_NAME} \${PRODUCT_VERSION}"
OutFile "Miss-Clean-Setup-{$APP_VERSION}.exe"
InstallDir "C:\\xampp\\htdocs\\{$APP_DIR_NAME}"
InstallDirRegKey HKLM "\${PRODUCT_DIR_REGKEY}" ""
ShowInstDetails show
ShowUnInstDetails show
RequestExecutionLevel admin

SetCompressor /SOLID lzma
SetCompressorDictSize 64

!include "MUI2.nsh"
!include "FileFunc.nsh"

!define MUI_ICON "app-icon.ico"
!define MUI_UNICON "app-icon.ico"

!insertmacro MUI_PAGE_WELCOME
!insertmacro MUI_PAGE_DIRECTORY
!insertmacro MUI_PAGE_INSTFILES
!insertmacro MUI_PAGE_FINISH

!insertmacro MUI_UNPAGE_INSTFILES

!insertmacro MUI_LANGUAGE "English"

Section "Install" SEC01
  {$nsisFileEntries}
  SetOutPath "\$INSTDIR"
  File "files\\if0_41710498_rent.sql"
  File "files\\app-icon.ico"
  File "install-config.ini"
  File "post-install.bat"
  File "start-miss-clean.bat"

  CreateDirectory "\$SMPROGRAMS\\\${PRODUCT_NAME}"
  CreateShortCut "\$SMPROGRAMS\\\${PRODUCT_NAME}\\${APP_NAME}.lnk" "\$INSTDIR\\start-miss-clean.bat" "" "\$INSTDIR\\app-icon.ico" 0
  CreateShortCut "\$SMPROGRAMS\\\${PRODUCT_NAME}\\Uninstall.lnk" "\$INSTDIR\\uninst.exe" "" "" 0
  CreateShortCut "\$DESKTOP\\${APP_NAME}.lnk" "\$INSTDIR\\start-miss-clean.bat" "" "\$INSTDIR\\app-icon.ico" 0

  ExecWait '"\$INSTDIR\\post-install.bat" importdb'

  WriteUninstaller "\$INSTDIR\\uninst.exe"

  WriteRegStr \${PRODUCT_UNINST_ROOT_KEY} "\${PRODUCT_UNINST_KEY}" "DisplayName" "\${PRODUCT_NAME}"
  WriteRegStr \${PRODUCT_UNINST_ROOT_KEY} "\${PRODUCT_UNINST_KEY}" "UninstallString" "\$INSTDIR\\uninst.exe"
  WriteRegStr \${PRODUCT_UNINST_ROOT_KEY} "\${PRODUCT_UNINST_KEY}" "DisplayVersion" "\${PRODUCT_VERSION}"
  WriteRegStr \${PRODUCT_UNINST_ROOT_KEY} "\${PRODUCT_UNINST_KEY}" "Publisher" "\${PRODUCT_PUBLISHER}"
SectionEnd

Section "Uninstall"
  RMDir /r "\$INSTDIR"
  RMDir /r "\$SMPROGRAMS\\\${PRODUCT_NAME}"
  Delete "\$DESKTOP\\${APP_NAME}.lnk"
  DeleteRegKey \${PRODUCT_UNINST_ROOT_KEY} "\${PRODUCT_UNINST_KEY}"
  DeleteRegKey HKLM "\${PRODUCT_DIR_REGKEY}"
SectionEnd
NSIS;

file_put_contents($OUTPUT_DIR . '/setup.nsi', $nsiContent);

// ─── Windows Post-Install Batch ─────────────────────────────────────────────

// ─── Generate Router for PHP Built-in Server ───────────────────────────────

$routerCode = <<<'ROUTER'
<?php
/**
 * Router for PHP built-in server
 * Handles /public/ URL prefix and routes through public/index.php
 */
$uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Serve existing files directly from public/
$filePath = __DIR__ . '/public' . $uriPath;
if (file_exists($filePath) && !is_dir($filePath)) {
    return false;
}

// Handle /public/ prefix (strip it and serve from public/)
if (strpos($uriPath, '/public/') === 0) {
    $stripped = __DIR__ . '/public/' . substr($uriPath, 8);
    if (file_exists($stripped) && !is_dir($stripped)) {
        return false;
    }
}

// Route through application
require __DIR__ . '/public/index.php';
ROUTER;

file_put_contents($targetDir . '/router.php', $routerCode);

// ─── Generate App Icon (.ico) for Windows ───────────────────────────────────

$iconJpg = $targetDir . '/public/logo.jpg';
$iconPngIco = $targetDir . '/public/icon-256.png';
$icoFile = $targetDir . '/app-icon.ico';

if (file_exists($iconJpg)) {
    // Resize to 256x256 for ICO (standard Windows icon size)
    shell_exec("sips -z 256 256 -s format png \"$iconJpg\" --out \"$iconPngIco\" 2>/dev/null");
    if (file_exists($iconPngIco)) {
        createIco($iconPngIco, $icoFile);
        if (file_exists($icoFile)) {
            echo "  App icon (.ico) created.\n";
        }
        unlink($iconPngIco);
    }
}
// Also place a copy in output dir for NSIS compile-time access
if (file_exists($icoFile)) {
    copy($icoFile, $OUTPUT_DIR . '/app-icon.ico');
}

// ─── Windows Post-Install Batch ─────────────────────────────────────────────

$postInstallBat = <<<'BAT'
@echo off
setlocal enabledelayedexpansion
title Miss Clean - Post Install Configuration

set CONFIG_FILE=%~dp0install-config.ini
if not exist "%CONFIG_FILE%" (
    echo [ERROR] install-config.ini not found!
    echo Please re-run the installer.
    pause
    exit /b 1
)

for /f "tokens=1,2 delims==" %%a in ('type "%CONFIG_FILE%"') do (
    if "%%a"=="XAMPP_PATH" set XAMPP_PATH=%%b
    if "%%a"=="DB_PASSWORD" set DB_PASSWORD=%%b
    if "%%a"=="APP_DIR" set APP_DIR=%%b
)

if "%XAMPP_PATH%"=="" set XAMPP_PATH=C:\xampp
if "%APP_DIR%"=="" set APP_DIR=%~dp0

echo.
echo === Miss Clean Post-Install Configuration ===
echo.
echo XAMPP Path: %XAMPP_PATH%
echo App Directory: %APP_DIR%
echo.

echo [1/5] Configuring .env for local use...
set ENV_FILE=%APP_DIR%\.env

if exist "%ENV_FILE%" (
    copy "%ENV_FILE%" "%ENV_FILE%.bak" >nul 2>&1
    (
        echo # Application Configuration
        echo APP_NAME="Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ"
        echo APP_ENV=offline
        echo APP_URL=http://localhost/rent-miss-clean
        echo APP_DEBUG=false
        echo.
        echo # Development Database Configuration
        echo DB_HOST=localhost
        echo DB_USERNAME=root
        echo DB_PASSWORD=!DB_PASSWORD!
        echo DB_DATABASE=if0_41710498_rent
        echo.
        echo # Production Database Configuration
        echo PROD_DB_HOSTNAME=localhost
        echo PROD_DB_USERNAME=root
        echo PROD_DB_PASSWORD=!DB_PASSWORD!
        echo PROD_DB_DATABASE=if0_41710498_rent
        echo.
        echo # UI Configuration
        echo FONT_FAMILY="'Noto Sans Lao', sans-serif"
        echo.
        echo # Security
        echo APP_KEY=
        echo.
        echo # ImageKit Configuration (leave empty for offline)
        echo IMAGEKIT_PUBLIC_KEY=
        echo IMAGEKIT_PRIVATE_KEY=
        echo IMAGEKIT_URL_ENDPOINT=
    ) > "%ENV_FILE%"
    echo   .env configured successfully.
) else (
    echo   [WARNING] .env file not found at expected location.
)

if /i "%1"=="importdb" (
    echo [2/5] Starting XAMPP services...
    echo   Starting XAMPP MySQL...
    net start mysql80 2>nul || net start mysql 2>nul || (
        echo   Trying direct start...
        start /b "" "!XAMPP_PATH!\mysql\bin\mysqld" --console 2>nul
    )
    timeout /t 5 /nobreak >nul

    echo [3/5] Importing database...

    set MYSQL_BIN=%XAMPP_PATH%\mysql\bin
    if not exist "!MYSQL_BIN!\mysql.exe" (
        echo   [WARNING] MySQL binary not found, trying PATH...
        set MYSQL_BIN=
    )

    set SQL_FILE=%APP_DIR%\if0_41710498_rent.sql
    if not exist "!SQL_FILE!" (
        echo   [ERROR] SQL file not found: !SQL_FILE!
        pause
        exit /b 1
    )

    echo   Creating database if0_41710498_rent...
    if "!DB_PASSWORD!"=="" (
        "!MYSQL_BIN!\mysql" -u root -e "CREATE DATABASE IF NOT EXISTS `if0_41710498_rent` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;" 2>nul
        if errorlevel 1 mysql -u root -e "CREATE DATABASE IF NOT EXISTS `if0_41710498_rent` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;" 2>nul
    ) else (
        "!MYSQL_BIN!\mysql" -u root -p!DB_PASSWORD! -e "CREATE DATABASE IF NOT EXISTS `if0_41710498_rent` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;" 2>nul
        if errorlevel 1 mysql -u root -p!DB_PASSWORD! -e "CREATE DATABASE IF NOT EXISTS `if0_41710498_rent` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;" 2>nul
    )

    echo   Importing data...
    if "!DB_PASSWORD!"=="" (
        "!MYSQL_BIN!\mysql" -u root if0_41710498_rent < "!SQL_FILE!" 2>nul
        if errorlevel 1 mysql -u root if0_41710498_rent < "!SQL_FILE!" 2>nul
    ) else (
        "!MYSQL_BIN!\mysql" -u root -p!DB_PASSWORD! if0_41710498_rent < "!SQL_FILE!" 2>nul
        if errorlevel 1 mysql -u root -p!DB_PASSWORD! if0_41710498_rent < "!SQL_FILE!" 2>nul
    )

    if errorlevel 1 (
        echo   [WARNING] Database import failed. Make sure MySQL is running in XAMPP.
    ) else (
        echo   Database imported successfully!
    )
)

echo [4/5] Installing XAMPP Windows services (auto-start on boot)...
echo   Installing Apache service...
"%XAMPP_PATH%\apache\bin\httpd.exe" -k install 2>nul
sc config apache2.4 start=auto 2>nul
echo   Installing MySQL service...
"%XAMPP_PATH%\mysql\bin\mysqld.exe" --install mysql80 2>nul
sc config mysql80 start=auto 2>nul
echo   Installing ProFTPD service...
"%XAMPP_PATH\proftpd\bin\proftpd.exe" --install 2>nul
sc config proftpd start=auto 2>nul
echo   Starting services...
net start apache2.4 2>nul
net start mysql80 2>nul
net start proftpd 2>nul
echo   XAMPP services installed and set to auto-start on boot.

echo [5/5] Creating shortcut in XAMPP htdocs...
set HTDOCS_PATH=%XAMPP_PATH%\htdocs\rent-miss-clean
if exist "%HTDOCS_PATH%" rmdir /s /q "%HTDOCS_PATH%" 2>nul
mklink /j "%HTDOCS_PATH%" "%APP_DIR%" >nul 2>&1
if errorlevel 1 (
    if not exist "%HTDOCS_PATH%" mkdir "%HTDOCS_PATH%"
    xcopy "%APP_DIR%\*" "%HTDOCS_PATH%\" /e /i /q /y >nul 2>&1
)

echo.
echo === Installation Complete! ===
echo.
echo Access: http://localhost/rent-miss-clean
echo Login:  admin / Admin123
echo.
echo XAMPP services (Apache, MySQL, ProFTPD) are set to auto-start on boot.
echo.

del "%CONFIG_FILE%" 2>nul
pause
exit /b 0
BAT;

file_put_contents($OUTPUT_DIR . '/post-install.bat', $postInstallBat);

// ─── Windows install-config.ini ─────────────────────────────────────────────

$installConfig = <<<INI
XAMPP_PATH=C:\\xampp
DB_PASSWORD=
APP_DIR=C:\\xampp\\htdocs\\{$APP_DIR_NAME}
INI;
file_put_contents($OUTPUT_DIR . '/install-config.ini', $installConfig);

// ─── Windows Start Batch ────────────────────────────────────────────────────

$startBat = <<<'BAT'
@echo off
title Miss Clean - ຊຸດໄໝໃຫ້ເຊົ່າ
echo.
echo === Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ ===
echo.
echo Starting XAMPP services...
net start apache2.4 2>nul || echo Apache service starting...
net start mysql80 2>nul || net start mysql 2>nul || echo MySQL service starting...
net start proftpd 2>nul || echo ProFTPD service starting...
echo.
echo Opening application in your browser...
echo.
start http://localhost/rent-miss-clean
echo Press any key to close this window...
pause >nul
BAT;

file_put_contents($OUTPUT_DIR . '/start-miss-clean.bat', $startBat);

// ─── Compile NSIS → .exe ───────────────────────────────────────────────────

$exeFile = $OUTPUT_DIR . '/Miss-Clean-Setup-' . $APP_VERSION . '.exe';
$nsiFile = $OUTPUT_DIR . '/setup.nsi';

echo "  Compiling NSIS script...\n";
exec("cd " . escapeshellarg($OUTPUT_DIR) . " && makensis setup.nsi 2>&1", $nsisOut, $nsisExitCode);
if ($nsisExitCode === 0) {
    echo "  OK — " . formatFileSize(filesize($exeFile)) . "\n";
} else {
    echo "  FAILED (exit code $nsisExitCode):\n";
    foreach ($nsisOut as $line) echo "    $line\n";
}

// ═══════════════════════════════════════════════════════════════════════════
// macOS INSTALLER
// ═══════════════════════════════════════════════════════════════════════════

echo "[5/6] Generating macOS installer (.dmg)...\n";

$macDir = $OUTPUT_DIR . '/mac';
$macFilesDir = $macDir . '/files';
$macAppDir = $macDir . '/Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ.app/Contents';

if (!is_dir($macFilesDir)) mkdir($macFilesDir, 0755, true);
if (!is_dir($macAppDir . '/MacOS')) mkdir($macAppDir . '/MacOS', 0755, true);
if (!is_dir($macAppDir . '/Resources')) mkdir($macAppDir . '/Resources', 0755, true);

// Copy app files to mac bundle
cpdir($targetDir, $macFilesDir);
if (file_exists($sqlFile)) {
    copy($sqlFile, $macFilesDir . '/if0_41710498_rent.sql');
}

// ─── macOS Install Command ──────────────────────────────────────────────────

$installCommand = <<<'SHELL'
#!/bin/bash

APP_NAME="Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ"
APP_DIR_NAME="rent-miss-clean"
DB_NAME="if0_41710498_rent"
PORT=8080

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Get the directory where this script is located
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
cp -R "$SCRIPT_DIR/files/" "$INSTALL_DIR/"
if [ -f "$SCRIPT_DIR/files/if0_41710498_rent.sql" ]; then
    cp "$SCRIPT_DIR/files/if0_41710498_rent.sql" "$INSTALL_DIR/"
fi
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

    # If can't connect, check if another MySQL binary can connect first
    # (e.g. XAMPP's client found first but Homebrew's server is already running)
    if ! "$MYSQL_CMD" -u root -e "SELECT 1" >/dev/null 2>&1; then
        for alt in \
            "/opt/homebrew/opt/mysql/bin/mysql" \
            "/opt/homebrew/opt/mariadb/bin/mysql" \
            "/usr/local/mysql/bin/mysql" \
            "/Applications/MAMP/Library/bin/mysql" \
            "/Applications/XAMPP/xamppfiles/bin/mysql"; do
            if [ -x "$alt" ] && [ "$alt" != "$MYSQL_CMD" ] && "$alt" -u root -e "SELECT 1" >/dev/null 2>&1; then
                MYSQL_CMD="$alt"
                echo -e "  Using running MySQL at: ${GREEN}$MYSQL_CMD${NC}"
                break
            fi
        done
    fi

    # Ensure MySQL is running
    if ! "$MYSQL_CMD" -u root -e "SELECT 1" >/dev/null 2>&1; then
        echo -e "  ${YELLOW}Starting MySQL...${NC}"

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

    # Try common passwords
    for pw in "" "Admin123" "root" "password" "mysql"; do
        if "$MYSQL_CMD" -u root ${pw:+-p"$pw"} -e "SELECT 1" >/dev/null 2>&1; then
            DB_PASSWORD="$pw"
            echo -e "  ${GREEN}✓${NC} Connected (password: ${pw:+****})$([ -z "$pw" ] && echo 'empty')"
            break
        fi
    done

    if [ -z "$DB_PASSWORD" ] && [ "$DB_PASSWORD" != "" ]; then
        # Last check — empty string already tried in the loop above
        :
    fi
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
if [ -n "$MYSQL_CMD" ] && [ -n "$DB_PASSWORD" ] || [ -n "$MYSQL_CMD" ] && [ -z "$DB_PASSWORD" ]; then
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
echo -e "${YELLOW}[5/6]${NC} Configuring XAMPP auto-start on boot..."

XAMPP_DIR="/Applications/XAMPP/xamppfiles"
if [ -d "$XAMPP_DIR" ]; then
    echo -e "  ${YELLOW}XAMPP detected. Setting up LaunchDaemons...${NC}"

    PLIST_DIR="/Library/LaunchDaemons"
    SVC_SCRIPT="$XAMPP_DIR/xampp"

    # Request admin privileges via osascript
    ADMIN_PASS=$(osascript -e 'Tell application "System Events" to display dialog "Enter your Mac password to configure XAMPP auto-start on boot:" default answer "" with hidden answer with icon caution with title "Miss Clean Installer"' -e 'text returned of result' 2>/dev/null)

    if [ -n "$ADMIN_PASS" ]; then
        # Helper to write a plist file using sudo
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

        # Also start services now
        echo "$ADMIN_PASS" | sudo -S "$SVC_SCRIPT" startapache 2>/dev/null
        echo "$ADMIN_PASS" | sudo -S "$SVC_SCRIPT" startmysql 2>/dev/null
        echo -e "  ${GREEN}✓${NC} XAMPP services set to auto-start on boot"
    else
        echo -e "  ${YELLOW}⚠${NC} Skipped (no password provided). XAMPP services won't auto-start on boot."
        echo -e "  To enable manually later, see: https://gist.github.com/ozgrozer/aa3d0fe4d8c9ae8b04a3622f2e55fc04"
    fi
else
    echo -e "  ${YELLOW}⚠ XAMPP not found. Skipping auto-start setup.${NC}"
fi

# ── Step 6: Create Launcher App ─────────────────────────────────────────
echo -e "${YELLOW}[6/6]${NC} Creating launcher application..."

LAUNCHER_APP="$HOME/Applications/Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ.app"
mkdir -p "$LAUNCHER_APP/Contents/MacOS"
mkdir -p "$LAUNCHER_APP/Contents/Resources"

cat > "$LAUNCHER_APP/Contents/MacOS/MissClean" << 'LAUNCHER'
#!/bin/bash
APP_DIR="$HOME/Library/Application Support/rent-miss-clean"
PORT=8080
DB_NAME="if0_41710498_rent"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Check if PHP is available
if ! command -v php &>/dev/null; then
    echo -e "${RED}PHP is not installed.${NC}"
    echo "PHP comes pre-installed on macOS. If missing, install via: brew install php"
    exit 1
fi

# Check if app directory exists
if [ ! -d "$APP_DIR" ]; then
    echo -e "${RED}Application not installed.${NC}"
    echo "Please run the installer first."
    exit 1
fi

cd "$APP_DIR"

# ── Ensure XAMPP services are running ──────────────────────────────────
XAMPP_SVC="/Applications/XAMPP/xamppfiles/xampp"
XAMPP_MYSQL="/Applications/XAMPP/xamppfiles/bin/mysql"
if [ -f "$XAMPP_MYSQL" ]; then
    if ! "$XAMPP_MYSQL" -u root -e "SELECT 1" >/dev/null 2>&1; then
        echo -e "${YELLOW}Starting XAMPP services...${NC}"
        ADMIN_PASS=$(osascript -e 'Tell application "System Events" to display dialog "Enter your Mac password to start XAMPP (MySQL + Apache):" default answer "" with hidden answer with icon caution with title "Miss Clean"' -e 'text returned of result' 2>/dev/null)
        if [ -n "$ADMIN_PASS" ]; then
            echo "$ADMIN_PASS" | sudo -S "$XAMPP_SVC" startmysql >/dev/null 2>&1
            echo "$ADMIN_PASS" | sudo -S "$XAMPP_SVC" startapache >/dev/null 2>&1
            sleep 3
        fi
    fi
else
    # Fallback: use standalone MySQL (Homebrew, MAMP, etc.)
    echo -e "${YELLOW}Checking MySQL...${NC}"
    MYSQL_CMD=""
    for candidate in \
        "$(which mysql 2>/dev/null)" \
        "/usr/local/mysql/bin/mysql" \
        "/opt/homebrew/opt/mysql/bin/mysql" \
        "/opt/homebrew/opt/mariadb/bin/mysql" \
        "/Applications/MAMP/Library/bin/mysql"; do
        if [ -x "$candidate" ]; then
            MYSQL_CMD="$candidate"
            break
        fi
    done
    if [ -n "$MYSQL_CMD" ] && ! "$MYSQL_CMD" -u root -e "SELECT 1" >/dev/null 2>&1; then
        echo -e "  ${YELLOW}Starting MySQL...${NC}"
        MYSQLD=$(dirname "$MYSQL_CMD")/mysqld_safe
        [ -f "$MYSQLD" ] && "$MYSQLD" --skip-grant-tables >/dev/null 2>&1 && sleep 3
        if command -v brew &>/dev/null; then
            brew services start mysql 2>/dev/null || brew services start mariadb 2>/dev/null || true
            sleep 2
        fi
        sleep 2
    fi
fi

# ── Start PHP Server ────────────────────────────────────────────────────
echo ""
echo "============================================"
echo "  Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ"
echo "============================================"
echo ""
echo -e "${GREEN}Starting server...${NC}"
echo ""
echo -e "  Open:  ${YELLOW}http://localhost:$PORT${NC}"
echo -e "  Login: admin / Admin123"
echo ""
echo "  Press Ctrl+C to stop the server"
echo ""

# Start PHP built-in server
php -S localhost:$PORT router.php &
PHP_PID=$!

# Open browser
sleep 1
open "http://localhost:$PORT"

# Keep running
wait $PHP_PID
LAUNCHER

chmod +x "$LAUNCHER_APP/Contents/MacOS/MissClean"

# Create Info.plist
cat > "$LAUNCHER_APP/Contents/Info.plist" << PLIST
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>CFBundleExecutable</key>
    <string>MissClean</string>
    <key>CFBundleIdentifier</key>
    <string>com.missclean.app</string>
    <key>CFBundleName</key>
    <string>Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ</string>
    <key>CFBundleDisplayName</key>
    <string>Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ</string>
    <key>CFBundleIconFile</key>
    <string>app-icon</string>
    <key>CFBundleVersion</key>
    <string>1.0.0</string>
    <key>CFBundleShortVersionString</key>
    <string>1.0.0</string>
    <key>CFBundleInfoDictionaryVersion</key>
    <string>6.0</string>
    <key>NSHighResolutionCapable</key>
    <true/>
</dict>
</plist>
PLIST

# Copy app icon from DMG bundle
SCRIPT_SRC="$(cd "$(dirname "$0")" && pwd)"
if [ -f "$SCRIPT_SRC/Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ.app/Contents/Resources/app-icon.icns" ]; then
    cp "$SCRIPT_SRC/Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ.app/Contents/Resources/app-icon.icns" "$LAUNCHER_APP/Contents/Resources/"
    echo -e "  ${GREEN}✓${NC} App icon installed"
fi

echo -e "  ${GREEN}✓${NC} Launcher created at $LAUNCHER_APP"

# ── Done ─────────────────────────────────────────────────────────────────
echo ""
echo "============================================"
echo -e "  ${GREEN}Installation Complete!${NC}"
echo "============================================"
echo ""
echo "  To start the app:"
echo "    Open: $LAUNCHER_APP"
echo "    Or:   http://localhost:$PORT"
echo ""
echo "  Login: admin / Admin123"
echo ""
echo "  PHP built-in server will start automatically."
echo "  Make sure MySQL/MariaDB is running for database access."
echo ""

open "$LAUNCHER_APP"
SHELL;

file_put_contents($macDir . '/Install Miss Clean.command', $installCommand);
chmod($macDir . '/Install Miss Clean.command', 0755);

// ─── macOS Start Command (standalone) ───────────────────────────────────────

$startCommand = <<<'SHELL'
#!/bin/bash
APP_DIR="$HOME/Library/Application Support/rent-miss-clean"
PORT=8080

if [ ! -d "$APP_DIR" ]; then
    echo "Error: Application not installed."
    echo "Please run 'Install Miss Clean.command' first."
    read -p "Press Enter to exit..."
    exit 1
fi

cd "$APP_DIR"

# ── Ensure XAMPP services are running ──────────────────────────────────
XAMPP_SVC="/Applications/XAMPP/xamppfiles/xampp"
XAMPP_MYSQL="/Applications/XAMPP/xamppfiles/bin/mysql"
if [ -f "$XAMPP_MYSQL" ]; then
    if ! "$XAMPP_MYSQL" -u root -e "SELECT 1" >/dev/null 2>&1; then
        echo "Starting XAMPP services..."
        ADMIN_PASS=$(osascript -e 'Tell application "System Events" to display dialog "Enter your Mac password to start XAMPP (MySQL + Apache):" default answer "" with hidden answer with icon caution with title "Miss Clean"' -e 'text returned of result' 2>/dev/null)
        if [ -n "$ADMIN_PASS" ]; then
            echo "$ADMIN_PASS" | sudo -S "$XAMPP_SVC" startmysql >/dev/null 2>&1
            echo "$ADMIN_PASS" | sudo -S "$XAMPP_SVC" startapache >/dev/null 2>&1
            sleep 3
        fi
    fi
else
    echo "Checking MySQL..."
    MYSQL_CMD=""
    for candidate in \
        "$(which mysql 2>/dev/null)" \
        "/usr/local/mysql/bin/mysql" \
        "/opt/homebrew/opt/mysql/bin/mysql" \
        "/opt/homebrew/opt/mariadb/bin/mysql" \
        "/Applications/MAMP/Library/bin/mysql"; do
        if [ -x "$candidate" ]; then
            MYSQL_CMD="$candidate"
            break
        fi
    done
    if [ -n "$MYSQL_CMD" ] && ! "$MYSQL_CMD" -u root -e "SELECT 1" >/dev/null 2>&1; then
        echo "  Starting MySQL..."
        MYSQLD=$(dirname "$MYSQL_CMD")/mysqld_safe
        [ -f "$MYSQLD" ] && "$MYSQLD" --skip-grant-tables >/dev/null 2>&1 && sleep 3
        if command -v brew &>/dev/null; then
            brew services start mysql 2>/dev/null || brew services start mariadb 2>/dev/null || true
            sleep 2
        fi
        sleep 2
    fi
fi

echo ""
echo "============================================"
echo "  Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ"
echo "============================================"
echo ""
echo "Starting PHP server..."
echo ""
echo "  Open:  http://localhost:$PORT"
echo "  Login: admin / Admin123"
echo ""
echo "  Press Ctrl+C to stop"
echo ""

php -S localhost:$PORT router.php &
PHP_PID=$!
sleep 1
open "http://localhost:$PORT"
wait $PHP_PID
SHELL;

file_put_contents($macDir . '/Start Miss Clean.command', $startCommand);
chmod($macDir . '/Start Miss Clean.command', 0755);

// ─── Write Launcher Executable into .app Bundle ─────────────────────────────

$launcherScript = <<<'LAUNCHER'
#!/bin/bash
APP_DIR="$HOME/Library/Application Support/rent-miss-clean"
PORT=8080

if [ ! -d "$APP_DIR" ]; then
    osascript -e 'tell app "System Events" to display dialog "Miss Clean is not installed yet.\n\nPlease run Install Miss Clean.command first." buttons {"OK"} default button 1 with icon stop with title "Miss Clean"'
    exit 1
fi

cd "$APP_DIR"

# ── Ensure XAMPP services are running ──────────────────────────────────
XAMPP_SVC="/Applications/XAMPP/xamppfiles/xampp"
XAMPP_MYSQL="/Applications/XAMPP/xamppfiles/bin/mysql"
if [ -f "$XAMPP_MYSQL" ]; then
    if ! "$XAMPP_MYSQL" -u root -e "SELECT 1" >/dev/null 2>&1; then
        ADMIN_PASS=$(osascript -e 'Tell application "System Events" to display dialog "Enter your Mac password to start XAMPP (MySQL + Apache):" default answer "" with hidden answer with icon caution with title "Miss Clean"' -e 'text returned of result' 2>/dev/null)
        if [ -n "$ADMIN_PASS" ]; then
            echo "$ADMIN_PASS" | sudo -S "$XAMPP_SVC" startmysql >/dev/null 2>&1
            echo "$ADMIN_PASS" | sudo -S "$XAMPP_SVC" startapache >/dev/null 2>&1
            sleep 3
        fi
    fi
else
    MYSQL_CMD=""
    for candidate in \
        "$(which mysql 2>/dev/null)" \
        "/usr/local/mysql/bin/mysql" \
        "/opt/homebrew/opt/mysql/bin/mysql" \
        "/opt/homebrew/opt/mariadb/bin/mysql" \
        "/Applications/MAMP/Library/bin/mysql"; do
        if [ -x "$candidate" ]; then
            MYSQL_CMD="$candidate"
            break
        fi
    done
    if [ -n "$MYSQL_CMD" ] && ! "$MYSQL_CMD" -u root -e "SELECT 1" >/dev/null 2>&1; then
        MYSQLD=$(dirname "$MYSQL_CMD")/mysqld_safe
        [ -f "$MYSQLD" ] && "$MYSQLD" --skip-grant-tables >/dev/null 2>&1 && sleep 3
        if command -v brew &>/dev/null; then
            brew services start mysql 2>/dev/null || brew services start mariadb 2>/dev/null || true
            sleep 2
        fi
        sleep 2
    fi
fi

osascript -e 'tell app "System Events" to display dialog "Miss Clean server is starting...\n\nOpen: http://localhost:'$PORT'\nLogin: admin / Admin123" buttons {"OK"} default button 1 with title "Miss Clean" giving up after 3'

php -S localhost:$PORT router.php > /dev/null 2>&1 &
PHP_PID=$!
sleep 1
open "http://localhost:$PORT"
wait $PHP_PID
LAUNCHER;

file_put_contents($macAppDir . '/MacOS/MissClean', $launcherScript);
chmod($macAppDir . '/MacOS/MissClean', 0755);

// Write Info.plist for DMG's .app bundle
$dmgInfoPlist = <<<PLIST
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>CFBundleExecutable</key>
    <string>MissClean</string>
    <key>CFBundleIdentifier</key>
    <string>com.missclean.app</string>
    <key>CFBundleName</key>
    <string>{$APP_NAME}</string>
    <key>CFBundleDisplayName</key>
    <string>{$APP_NAME}</string>
    <key>CFBundleIconFile</key>
    <string>app-icon</string>
    <key>CFBundleVersion</key>
    <string>{$APP_VERSION}</string>
    <key>CFBundleShortVersionString</key>
    <string>{$APP_VERSION}</string>
    <key>CFBundleInfoDictionaryVersion</key>
    <string>6.0</string>
    <key>NSHighResolutionCapable</key>
    <true/>
</dict>
</plist>
PLIST;
file_put_contents($macAppDir . '/Info.plist', $dmgInfoPlist);

// ─── Convert logo.jpg to .icns ──────────────────────────────────────────────

$iconJpg = $targetDir . '/public/logo.jpg';
$iconPng = $macDir . '/icon-512.png';
$iconIcns = $macAppDir . '/Resources/app-icon.icns';

if (file_exists($iconJpg)) {
    shell_exec("sips -s format png \"$iconJpg\" --out \"$iconPng\" 2>/dev/null");
    if (file_exists($iconPng)) {
        $iconsetDir = $macDir . '/AppIcon.iconset';
        if (!is_dir($iconsetDir)) mkdir($iconsetDir, 0755, true);

        $sizes = [16, 32, 64, 128, 256, 512];
        foreach ($sizes as $size) {
            $sipsCmd = "sips -z $size $size \"$iconPng\" --out \"$iconsetDir/icon_{$size}x{$size}.png\" 2>/dev/null";
            shell_exec($sipsCmd);
            if ($size < 512) {
                $retina = $size * 2;
                $sipsCmd2 = "sips -z $retina $retina \"$iconPng\" --out \"$iconsetDir/icon_{$size}x{$size}@2x.png\" 2>/dev/null";
                shell_exec($sipsCmd2);
            }
        }

        shell_exec("iconutil -c icns \"$iconsetDir\" -o \"$iconIcns\" 2>/dev/null");
        rmrf($iconsetDir);
        unlink($iconPng);

        if (file_exists($iconIcns)) {
            echo "  App icon (.icns) created.\n";
        }
    }
}

// ─── Build DMG ──────────────────────────────────────────────────────────────

$dmgFile = $OUTPUT_DIR . '/Miss-Clean-Setup-' . $APP_VERSION . '.dmg';
$dmgTempDir = $OUTPUT_DIR . '/dmg-tmp';
$dmgRootDir = $dmgTempDir . '/Miss Clean Setup';

if (!is_dir($dmgRootDir)) mkdir($dmgRootDir, 0755, true);

// Copy everything into DMG staging area
cpdir($macDir, $dmgRootDir);

// Remove the source files copy (already included in the .command installer)
// Add Applications folder symlink
shell_exec("ln -s /Applications " . escapeshellarg($dmgRootDir . '/Applications') . " 2>/dev/null");

// Create the DMG
$dmgSize = '20m';
$tempDmg = $OUTPUT_DIR . '/miss-clean-tmp.dmg';

shell_exec("hdiutil create -size $dmgSize -fs HFS+ -volname \"Miss Clean Setup\" \"$tempDmg\" 2>/dev/null");

// Mount and copy
$mountOutput = shell_exec("hdiutil attach \"$tempDmg\" -mountpoint /Volumes/miss-clean-install-tmp 2>/dev/null");
if (strpos($mountOutput ?? '', '/Volumes/miss-clean-install-tmp') !== false) {
    // Use ditto to preserve permissions and metadata
    shell_exec("ditto " . escapeshellarg($dmgRootDir) . " /Volumes/miss-clean-install-tmp 2>/dev/null");
    // Explicitly set executable permissions on .command files and .app bundles
    shell_exec("chmod -R +x /Volumes/miss-clean-install-tmp/*.command /Volumes/miss-clean-install-tmp/*.app/Contents/MacOS/* 2>/dev/null");
    // Remove quarantine attribute so Gatekeeper doesn't block
    shell_exec("xattr -d -r com.apple.quarantine /Volumes/miss-clean-install-tmp/*.command /Volumes/miss-clean-install-tmp/*.app 2>/dev/null; true");
    shell_exec("hdiutil detach /Volumes/miss-clean-install-tmp 2>/dev/null");
    shell_exec("hdiutil convert \"$tempDmg\" -format UDZO -imagekey zlib-level=9 -o \"$dmgFile\" 2>/dev/null");
    unlink($tempDmg);
}

// Clean up
rmrf($dmgTempDir);

if (file_exists($dmgFile)) {
    echo "  DMG created: " . formatFileSize(filesize($dmgFile)) . "\n";
} else {
    echo "  DMG creation attempted — check permissions.\n";
}

// ═══════════════════════════════════════════════════════════════════════════
// SUMMARY
// ═══════════════════════════════════════════════════════════════════════════

echo "\n[6/6] Cleaning up...\n";

// Remove working directories but keep the final outputs
rmrf($OUTPUT_DIR . '/files');
rmrf($OUTPUT_DIR . '/mac');
unlink($OUTPUT_DIR . '/setup.nsi');
unlink($OUTPUT_DIR . '/post-install.bat');
unlink($OUTPUT_DIR . '/install-config.ini');
unlink($OUTPUT_DIR . '/start-miss-clean.bat');
@unlink($OUTPUT_DIR . '/app-icon.ico');

echo "\n=== Build Complete! ===\n\n";

$outputs = [];
if (file_exists($exeFile)) $outputs[] = "  • " . $exeFile . " (" . formatFileSize(filesize($exeFile)) . ")";
if (file_exists($dmgFile)) $outputs[] = "  • " . $dmgFile . " (" . formatFileSize(filesize($dmgFile)) . ")";

echo "Generated:\n";
foreach ($outputs as $o) echo $o . "\n";

echo "\nInstall on target machine:\n";
echo "  Windows: Run Miss-Clean-Setup-{$APP_VERSION}.exe (requires XAMPP)\n";
echo "  macOS:   Open Miss-Clean-Setup-{$APP_VERSION}.dmg → run 'Install Miss Clean.command'\n";
echo "\nDone!\n";
