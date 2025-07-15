@echo off
setlocal enabledelayedexpansion

echo ========================================
echo E-Commerce Nginx Deployment Test
echo ========================================
echo.

set BASE_URL=http://localhost
set PASSED=0
set FAILED=0

REM Check if curl is available
curl --version >nul 2>&1
if %errorlevel% NEQ 0 (
    echo [ERROR] curl is required but not found
    echo Please install curl or use manual testing
    pause
    exit /b 1
)

echo Testing URL: %BASE_URL%
echo.

REM Test 1: Basic Connectivity
echo [TEST] Basic Connectivity
curl -s -o nul -w "%%{http_code}" %BASE_URL%/ > temp_status.txt
set /p STATUS=<temp_status.txt
del temp_status.txt
if "!STATUS!" == "200" (
    echo [PASS] Home page accessible ^(HTTP !STATUS!^)
    set /a PASSED+=1
) else (
    echo [FAIL] Home page not accessible ^(HTTP !STATUS!^)
    set /a FAILED+=1
)
echo.

REM Test 2: PHP Processing
echo [TEST] PHP Processing
curl -s %BASE_URL%/index.php > temp_response.txt
findstr /C:"<!DOCTYPE" temp_response.txt >nul
if %errorlevel% EQU 0 (
    echo [PASS] PHP files are being processed correctly
    set /a PASSED+=1
) else (
    echo [FAIL] PHP files are not being processed
    set /a FAILED+=1
)
del temp_response.txt
echo.

REM Test 3: Clean URLs
echo [TEST] Clean URLs ^(URL Rewriting^)
set pages=about menu contact cart
for %%p in (%pages%) do (
    curl -s -o nul -w "%%{http_code}" %BASE_URL%/%%p > temp_status.txt
    set /p STATUS=<temp_status.txt
    del temp_status.txt
    if "!STATUS!" == "200" (
        echo [PASS] Clean URL /%%p works ^(HTTP !STATUS!^)
        set /a PASSED+=1
    ) else (
        echo [FAIL] Clean URL /%%p failed ^(HTTP !STATUS!^)
        set /a FAILED+=1
    )
)
echo.

REM Test 4: Static Files
echo [TEST] Static Files Serving

REM Test CSS
curl -s -o nul -w "%%{http_code}" %BASE_URL%/css/style.css > temp_status.txt
set /p STATUS=<temp_status.txt
del temp_status.txt
if "!STATUS!" == "200" (
    echo [PASS] CSS files served correctly
    set /a PASSED+=1
) else (
    echo [FAIL] CSS files not served ^(HTTP !STATUS!^)
    set /a FAILED+=1
)

REM Test JS
curl -s -o nul -w "%%{http_code}" %BASE_URL%/js/script.js > temp_status.txt
set /p STATUS=<temp_status.txt
del temp_status.txt
if "!STATUS!" == "200" (
    echo [PASS] JavaScript files served correctly
    set /a PASSED+=1
) else (
    echo [FAIL] JavaScript files not served ^(HTTP !STATUS!^)
    set /a FAILED+=1
)

REM Test Images
curl -s -o nul -w "%%{http_code}" %BASE_URL%/images/home-img-1.jpg > temp_status.txt
set /p STATUS=<temp_status.txt
del temp_status.txt
if "!STATUS!" == "200" (
    echo [PASS] Image files served correctly
    set /a PASSED+=1
) else (
    echo [FAIL] Image files not served ^(HTTP !STATUS!^)
    set /a FAILED+=1
)
echo.

REM Test 5: Security Headers
echo [TEST] Security Headers
curl -s -I %BASE_URL%/ > temp_headers.txt

findstr /C:"X-Frame-Options" temp_headers.txt >nul
if %errorlevel% EQU 0 (
    echo [PASS] X-Frame-Options header present
    set /a PASSED+=1
) else (
    echo [FAIL] X-Frame-Options header missing
    set /a FAILED+=1
)

findstr /C:"X-Content-Type-Options" temp_headers.txt >nul
if %errorlevel% EQU 0 (
    echo [PASS] X-Content-Type-Options header present
    set /a PASSED+=1
) else (
    echo [FAIL] X-Content-Type-Options header missing
    set /a FAILED+=1
)

findstr /C:"X-XSS-Protection" temp_headers.txt >nul
if %errorlevel% EQU 0 (
    echo [PASS] X-XSS-Protection header present
    set /a PASSED+=1
) else (
    echo [FAIL] X-XSS-Protection header missing
    set /a FAILED+=1
)

del temp_headers.txt
echo.

REM Test 6: Security Restrictions
echo [TEST] Security Restrictions
set sensitive_files=config/config.php nginx.conf README.md DEPLOYMENT.md
for %%f in (%sensitive_files%) do (
    curl -s -o nul -w "%%{http_code}" %BASE_URL%/%%f > temp_status.txt
    set /p STATUS=<temp_status.txt
    del temp_status.txt
    if "!STATUS!" == "403" (
        echo [PASS] Access to %%f properly restricted ^(HTTP !STATUS!^)
        set /a PASSED+=1
    ) else if "!STATUS!" == "404" (
        echo [PASS] Access to %%f properly restricted ^(HTTP !STATUS!^)
        set /a PASSED+=1
    ) else (
        echo [FAIL] Access to %%f not restricted ^(HTTP !STATUS!^)
        set /a FAILED+=1
    )
)
echo.

REM Test 7: Admin Panel
echo [TEST] Admin Panel Access
curl -s -o nul -w "%%{http_code}" %BASE_URL%/admin/ > temp_status.txt
set /p STATUS=<temp_status.txt
del temp_status.txt
if "!STATUS!" == "200" (
    echo [PASS] Admin panel accessible
    set /a PASSED+=1
) else (
    echo [FAIL] Admin panel not accessible ^(HTTP !STATUS!^)
    set /a FAILED+=1
)
echo.

REM Test 8: Upload Directory
echo [TEST] Upload Directory
if exist "uploaded_img" (
    echo [PASS] Upload directory exists
    set /a PASSED+=1
) else (
    echo [FAIL] Upload directory not found
    set /a FAILED+=1
)
echo.

REM Test 9: Database Connection
echo [TEST] Database Connection
curl -s %BASE_URL%/menu.php > temp_response.txt 2>nul
findstr /I /C:"error" temp_response.txt >nul
if %errorlevel% NEQ 0 (
    findstr /I /C:"warning" temp_response.txt >nul
    if %errorlevel% NEQ 0 (
        findstr /I /C:"fatal" temp_response.txt >nul
        if %errorlevel% NEQ 0 (
            echo [PASS] No obvious database connection errors
            set /a PASSED+=1
        ) else (
            echo [FAIL] Possible database connection error detected
            set /a FAILED+=1
        )
    ) else (
        echo [FAIL] Possible database connection error detected
        set /a FAILED+=1
    )
) else (
    echo [FAIL] Possible database connection error detected
    set /a FAILED+=1
)
del temp_response.txt 2>nul
echo.

REM Test 10: Performance Check
echo [TEST] Performance Check
curl -s -o nul -w "%%{time_total}" %BASE_URL%/ > temp_time.txt
set /p TIME_TOTAL=<temp_time.txt
del temp_time.txt

REM Simple time check (approximate)
echo Response time: %TIME_TOTAL% seconds
if "%TIME_TOTAL%" LSS "1.000" (
    echo [PASS] Response time good
    set /a PASSED+=1
) else if "%TIME_TOTAL%" LSS "3.000" (
    echo [WARN] Response time acceptable
    set /a PASSED+=1
) else (
    echo [FAIL] Response time slow
    set /a FAILED+=1
)
echo.

REM Summary
echo ==========================================
echo Test Results Summary
echo ==========================================
echo Passed: %PASSED%
echo Failed: %FAILED%
echo.

if %FAILED% EQU 0 (
    echo [SUCCESS] All tests passed! Your E-Commerce application is working correctly with Nginx.
    echo.
    echo Visit your application: %BASE_URL%
) else (
    echo [WARNING] Some tests failed. Please check the issues above.
    echo.
    echo Common solutions:
    echo 1. Make sure Nginx and PHP-FPM are running
    echo 2. Check nginx configuration file
    echo 3. Verify file permissions
    echo 4. Check database connection
)

echo.
echo Press any key to exit...
pause >nul
