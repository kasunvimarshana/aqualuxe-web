@echo off
:: AquaLuxe Theme Deployment Script for Windows
:: This script prepares the theme for production deployment

echo 🌊 AquaLuxe Theme Deployment Script
echo ==================================

:: Check if we're in the right directory
if not exist "style.css" (
    echo [ERROR] This script must be run from the theme root directory
    pause
    exit /b 1
)

if not exist "functions.php" (
    echo [ERROR] This script must be run from the theme root directory
    pause
    exit /b 1
)

:: Check if Node.js is installed
node --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Node.js is not installed. Please install Node.js first.
    pause
    exit /b 1
)

:: Check if npm is installed
npm --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] npm is not installed. Please install npm first.
    pause
    exit /b 1
)

echo [INFO] Starting deployment process...

:: Install dependencies
echo [INFO] Installing Node.js dependencies...
npm install
if %errorlevel% neq 0 (
    echo [ERROR] Failed to install dependencies
    pause
    exit /b 1
)
echo [SUCCESS] Dependencies installed successfully

:: Build production assets
echo [INFO] Building production assets...
npm run production
if %errorlevel% neq 0 (
    echo [ERROR] Failed to build assets
    pause
    exit /b 1
)
echo [SUCCESS] Assets built successfully

:: Check if assets were created
if not exist "assets\dist" (
    echo [ERROR] Assets directory not found after build
    pause
    exit /b 1
)

echo [SUCCESS] Assets created in assets/dist/

:: Create deployment package
for /f "tokens=2" %%i in ('findstr "Version:" style.css') do set VERSION=%%i
set THEME_NAME=aqualuxe
set PACKAGE_NAME=%THEME_NAME%-%VERSION%

echo [INFO] Creating deployment package: %PACKAGE_NAME%

:: Create temporary directory
if exist "%TEMP%\%PACKAGE_NAME%" rmdir /s /q "%TEMP%\%PACKAGE_NAME%"
mkdir "%TEMP%\%PACKAGE_NAME%"

:: Copy theme files
echo [INFO] Copying theme files...
xcopy /E /I *.php "%TEMP%\%PACKAGE_NAME%\" >nul
xcopy /E /I *.css "%TEMP%\%PACKAGE_NAME%\" >nul
xcopy /E /I assets "%TEMP%\%PACKAGE_NAME%\assets\" >nul
xcopy /E /I inc "%TEMP%\%PACKAGE_NAME%\inc\" >nul
xcopy /E /I templates "%TEMP%\%PACKAGE_NAME%\templates\" >nul
xcopy /E /I woocommerce "%TEMP%\%PACKAGE_NAME%\woocommerce\" >nul
if exist languages xcopy /E /I languages "%TEMP%\%PACKAGE_NAME%\languages\" >nul
copy README.md "%TEMP%\%PACKAGE_NAME%\" >nul 2>&1

:: Remove development files
echo [INFO] Cleaning up development files...
if exist "%TEMP%\%PACKAGE_NAME%\assets\src" rmdir /s /q "%TEMP%\%PACKAGE_NAME%\assets\src"
if exist "%TEMP%\%PACKAGE_NAME%\node_modules" rmdir /s /q "%TEMP%\%PACKAGE_NAME%\node_modules"
if exist "%TEMP%\%PACKAGE_NAME%\package.json" del "%TEMP%\%PACKAGE_NAME%\package.json"
if exist "%TEMP%\%PACKAGE_NAME%\package-lock.json" del "%TEMP%\%PACKAGE_NAME%\package-lock.json"
if exist "%TEMP%\%PACKAGE_NAME%\webpack.mix.js" del "%TEMP%\%PACKAGE_NAME%\webpack.mix.js"
if exist "%TEMP%\%PACKAGE_NAME%\tailwind.config.js" del "%TEMP%\%PACKAGE_NAME%\tailwind.config.js"
if exist "%TEMP%\%PACKAGE_NAME%\.gitignore" del "%TEMP%\%PACKAGE_NAME%\.gitignore"
if exist "%TEMP%\%PACKAGE_NAME%\deploy.sh" del "%TEMP%\%PACKAGE_NAME%\deploy.sh"
if exist "%TEMP%\%PACKAGE_NAME%\deploy.bat" del "%TEMP%\%PACKAGE_NAME%\deploy.bat"

:: Create zip package (requires PowerShell)
echo [INFO] Creating ZIP package...
powershell -command "Compress-Archive -Path '%TEMP%\%PACKAGE_NAME%' -DestinationPath '%TEMP%\%PACKAGE_NAME%.zip' -Force"
if %errorlevel% neq 0 (
    echo [ERROR] Failed to create ZIP package
    pause
    exit /b 1
)

:: Copy to current directory
copy "%TEMP%\%PACKAGE_NAME%.zip" "." >nul
echo [SUCCESS] Package created: %PACKAGE_NAME%.zip

:: Cleanup
rmdir /s /q "%TEMP%\%PACKAGE_NAME%"
del "%TEMP%\%PACKAGE_NAME%.zip"

:: Get file size
for %%A in ("%PACKAGE_NAME%.zip") do set PACKAGE_SIZE=%%~zA
echo [SUCCESS] Deployment package ready: %PACKAGE_NAME%.zip (%PACKAGE_SIZE% bytes)

:: Deployment information
echo.
echo 📋 Pre-deployment Checklist:
echo ==============================
echo ✅ Assets built for production
echo ✅ Development files removed
echo ✅ Package created and compressed
echo.
echo 📦 Next Steps:
echo 1. Test the package on a staging site
echo 2. Upload to WordPress themes directory
echo 3. Activate the theme
echo 4. Configure theme settings in Customizer
echo 5. Set up WooCommerce if using e-commerce features
echo.
echo 🚀 WordPress Deployment:
echo ========================
echo Method 1 - Admin Upload:
echo 1. Go to WordPress Admin → Appearance → Themes
echo 2. Click 'Add New' → 'Upload Theme'
echo 3. Choose %PACKAGE_NAME%.zip
echo 4. Click 'Install Now'
echo.
echo Method 2 - FTP Upload:
echo 1. Extract %PACKAGE_NAME%.zip
echo 2. Upload the folder to /wp-content/themes/
echo 3. Activate in WordPress Admin
echo.
echo ⚡ Performance Recommendations:
echo ===============================
echo 1. Install a caching plugin (WP Rocket, W3 Total Cache)
echo 2. Use a CDN for static assets
echo 3. Optimize images before upload
echo 4. Enable GZIP compression
echo 5. Use PHP 8.0+ for better performance
echo.
echo 🔒 Security Recommendations:
echo ============================
echo 1. Keep WordPress core updated
echo 2. Use strong admin passwords
echo 3. Install a security plugin (Wordfence, Sucuri)
echo 4. Enable SSL/HTTPS
echo 5. Regular backups
echo.
echo [SUCCESS] Deployment script completed successfully!
echo [WARNING] Remember to test thoroughly before going live!
echo.
echo 🌊 Thank you for using AquaLuxe Theme!
echo ======================================

pause
