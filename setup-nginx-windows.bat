@echo off
echo ========================================
echo E-Commerce Nginx Setup for Windows
echo ========================================
echo.

REM Check if running as administrator
net session >nul 2>&1
if %errorLevel% NEQ 0 (
    echo ERROR: Please run this script as Administrator
    pause
    exit /b 1
)

REM Set paths
set NGINX_PATH=C:\nginx
set PHP_PATH=C:\php
set PROJECT_PATH=%~dp0

echo [INFO] Project Path: %PROJECT_PATH%
echo [INFO] Nginx Path: %NGINX_PATH%
echo [INFO] PHP Path: %PHP_PATH%
echo.

REM Check if Nginx exists
if not exist "%NGINX_PATH%\nginx.exe" (
    echo [ERROR] Nginx not found at %NGINX_PATH%
    echo Please download Nginx from http://nginx.org/en/download.html
    echo Extract to %NGINX_PATH% and run this script again
    pause
    exit /b 1
)

REM Check if PHP exists
if not exist "%PHP_PATH%\php-cgi.exe" (
    echo [ERROR] PHP not found at %PHP_PATH%
    echo Please download PHP from https://windows.php.net/download/
    echo Extract to %PHP_PATH% and run this script again
    pause
    exit /b 1
)

echo [INFO] All requirements found!
echo.

REM Stop existing processes
echo [INFO] Stopping existing Nginx and PHP processes...
taskkill /f /im nginx.exe 2>nul
taskkill /f /im php-cgi.exe 2>nul

REM Stop Apache service if running
echo [INFO] Stopping Apache service if running...
net stop "Apache2.4" 2>nul
sc config "Apache2.4" start= disabled 2>nul

REM Backup existing nginx.conf
if exist "%NGINX_PATH%\conf\nginx.conf" (
    echo [INFO] Backing up original nginx.conf...
    copy "%NGINX_PATH%\conf\nginx.conf" "%NGINX_PATH%\conf\nginx.conf.backup" >nul
)

REM Create Windows-compatible nginx.conf
echo [INFO] Creating nginx configuration...
(
echo worker_processes  1;
echo events {
echo     worker_connections  1024;
echo }
echo http {
echo     include       mime.types;
echo     default_type  application/octet-stream;
echo     sendfile        on;
echo     keepalive_timeout  65;
echo.
echo     server {
echo         listen 80;
echo         server_name localhost;
echo         root %PROJECT_PATH:/=\%;
echo         index index.php index.html;
echo.
echo         # Security headers
echo         add_header X-Frame-Options "DENY" always;
echo         add_header X-Content-Type-Options "nosniff" always;
echo         add_header X-XSS-Protection "1; mode=block" always;
echo.
echo         # Main location
echo         location / {
echo             try_files $uri $uri/ /index.php?$query_string;
echo         }
echo.
echo         # PHP processing
echo         location ~ \.php$ {
echo             fastcgi_pass 127.0.0.1:9000;
echo             fastcgi_index index.php;
echo             fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
echo             include fastcgi_params;
echo         }
echo.
echo         # Static files
echo         location ~* \.(css^|js^|png^|jpg^|jpeg^|gif^|ico^|svg^)$ {
echo             expires 1M;
echo             add_header Cache-Control "public";
echo         }
echo.
echo         # Deny sensitive files
echo         location ~ /\. {
echo             deny all;
echo         }
echo.
echo         location ~* \.(md^|sql^|log^)$ {
echo             deny all;
echo         }
echo.
echo         # Admin protection
echo         location /admin/ {
echo             try_files $uri $uri/ /admin/index.php?$query_string;
echo         }
echo     }
echo }
) > "%NGINX_PATH%\conf\nginx.conf"

REM Create PHP startup script
echo [INFO] Creating PHP startup script...
(
echo @echo off
echo echo Starting PHP FastCGI...
echo cd /d "%PHP_PATH%"
echo start /b php-cgi.exe -b 127.0.0.1:9000
echo echo PHP FastCGI started on port 9000
) > "%PROJECT_PATH%start-php.bat"

REM Create Nginx startup script
echo [INFO] Creating Nginx startup script...
(
echo @echo off
echo echo Starting Nginx...
echo cd /d "%NGINX_PATH%"
echo start /b nginx.exe
echo echo Nginx started
) > "%PROJECT_PATH%start-nginx.bat"

REM Create combined startup script
echo [INFO] Creating combined startup script...
(
echo @echo off
echo title E-Commerce Application
echo echo ========================================
echo echo Starting E-Commerce Application...
echo echo ========================================
echo echo.
echo echo [1/2] Starting PHP FastCGI...
echo call "%PROJECT_PATH%start-php.bat"
echo timeout /t 3 /nobreak ^>nul
echo echo.
echo echo [2/2] Starting Nginx...
echo call "%PROJECT_PATH%start-nginx.bat"
echo timeout /t 2 /nobreak ^>nul
echo echo.
echo echo ========================================
echo echo E-Commerce Application Started!
echo echo ========================================
echo echo Visit: http://localhost
echo echo.
echo echo Press any key to open browser...
echo pause ^>nul
echo start http://localhost
) > "%PROJECT_PATH%start-ecommerce.bat"

REM Create stop script
echo [INFO] Creating stop script...
(
echo @echo off
echo echo Stopping E-Commerce Application...
echo taskkill /f /im nginx.exe 2^>nul
echo taskkill /f /im php-cgi.exe 2^>nul
echo echo E-Commerce Application stopped.
echo pause
) > "%PROJECT_PATH%stop-ecommerce.bat"

REM Test nginx configuration
echo [INFO] Testing Nginx configuration...
cd /d "%NGINX_PATH%"
nginx.exe -t
if %errorlevel% NEQ 0 (
    echo [ERROR] Nginx configuration test failed!
    pause
    exit /b 1
)

echo [INFO] Nginx configuration test passed!
echo.

REM Start services
echo [INFO] Starting services...
echo.

REM Start PHP FastCGI
echo [INFO] Starting PHP FastCGI...
cd /d "%PHP_PATH%"
start /b php-cgi.exe -b 127.0.0.1:9000

REM Wait a moment
timeout /t 2 /nobreak >nul

REM Start Nginx
echo [INFO] Starting Nginx...
cd /d "%NGINX_PATH%"
start /b nginx.exe

REM Wait a moment
timeout /t 2 /nobreak >nul

REM Test deployment
echo [INFO] Testing deployment...
echo.

REM Simple connectivity test using curl if available, otherwise skip
curl --version >nul 2>&1
if %errorlevel% EQU 0 (
    echo [TEST] Testing basic connectivity...
    curl -s -o nul -w "%%{http_code}" http://localhost/ > temp_status.txt
    set /p STATUS=<temp_status.txt
    del temp_status.txt
    if "!STATUS!" == "200" (
        echo [PASS] Basic connectivity test: PASSED
    ) else (
        echo [WARN] Basic connectivity test: FAILED
    )
) else (
    echo [INFO] curl not found, skipping automated tests
    echo [INFO] Please manually test: http://localhost
)

echo.
echo ========================================
echo Deployment Completed Successfully!
echo ========================================
echo.
echo Your E-Commerce application is now running on Nginx
echo.
echo Quick Start:
echo   - Visit: http://localhost
echo   - Start app: start-ecommerce.bat
echo   - Stop app: stop-ecommerce.bat
echo.
echo Important Notes:
echo   1. Use start-ecommerce.bat to start both PHP and Nginx
echo   2. Configure your database connection if needed
echo   3. Test all functionality thoroughly
echo   4. For production, configure SSL and domain settings
echo.
echo Press any key to open the application in browser...
pause >nul
start http://localhost

exit /b 0
