# E-Commerce Nginx Deployment Script for Windows
# This script helps deploy the E-Commerce application to Nginx on Windows

param(
    [string]$NginxPath = "C:\nginx",
    [string]$PHPPath = "C:\php",
    [string]$ProjectPath = "C:\xampp\htdocs\E-Commerce"
)

# Colors for output
$Green = "Green"
$Yellow = "Yellow"
$Red = "Red"

function Write-Status {
    param([string]$Message)
    Write-Host "[INFO] $Message" -ForegroundColor $Green
}

function Write-Warning-Custom {
    param([string]$Message)
    Write-Host "[WARNING] $Message" -ForegroundColor $Yellow
}

function Write-Error-Custom {
    param([string]$Message)
    Write-Host "[ERROR] $Message" -ForegroundColor $Red
}

function Test-Administrator {
    $currentUser = [Security.Principal.WindowsIdentity]::GetCurrent()
    $principal = New-Object Security.Principal.WindowsPrincipal($currentUser)
    return $principal.IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
}

function Install-Nginx {
    Write-Status "Setting up Nginx for Windows..."
    
    if (!(Test-Path $NginxPath)) {
        Write-Warning-Custom "Nginx not found at $NginxPath"
        Write-Status "Please download Nginx from http://nginx.org/en/download.html"
        Write-Status "Extract to $NginxPath and run this script again"
        return $false
    }
    
    Write-Status "Nginx found at $NginxPath"
    return $true
}

function Install-PHP {
    Write-Status "Checking PHP installation..."
    
    if (!(Test-Path $PHPPath)) {
        Write-Warning-Custom "PHP not found at $PHPPath"
        Write-Status "Please download PHP from https://windows.php.net/download/"
        Write-Status "Extract to $PHPPath and run this script again"
        return $false
    }
    
    # Check if php-cgi.exe exists
    if (!(Test-Path "$PHPPath\php-cgi.exe")) {
        Write-Error-Custom "php-cgi.exe not found in $PHPPath"
        return $false
    }
    
    Write-Status "PHP found at $PHPPath"
    return $true
}

function Stop-ApacheService {
    Write-Status "Stopping Apache services if running..."
    
    $apacheServices = @("Apache2.4", "Apache", "httpd")
    
    foreach ($service in $apacheServices) {
        $svc = Get-Service -Name $service -ErrorAction SilentlyContinue
        if ($svc -and $svc.Status -eq "Running") {
            Stop-Service -Name $service -Force
            Set-Service -Name $service -StartupType Disabled
            Write-Status "Stopped and disabled $service"
        }
    }
}

function Configure-Nginx {
    Write-Status "Configuring Nginx..."
    
    $nginxConf = "$NginxPath\conf\nginx.conf"
    $projectNginxConf = "$ProjectPath\nginx.conf"
    
    if (!(Test-Path $projectNginxConf)) {
        Write-Error-Custom "nginx.conf not found in project directory"
        return $false
    }
    
    # Backup original nginx.conf
    if (Test-Path $nginxConf) {
        Copy-Item $nginxConf "$nginxConf.backup"
        Write-Status "Backup created for original nginx.conf"
    }
    
    # Read project nginx.conf and modify for Windows
    $content = Get-Content $projectNginxConf -Raw
    
    # Replace Unix paths with Windows paths
    $content = $content -replace "/var/www/html/E-Commerce", $ProjectPath.Replace("\", "/")
    $content = $content -replace "/var/run/php/php8.1-fpm.sock", "127.0.0.1:9000"
    $content = $content -replace "/var/log/nginx/", "$NginxPath/logs/".Replace("\", "/")
    
    # Create Windows-compatible nginx.conf
    $windowsNginxConf = @"
worker_processes  1;
events {
    worker_connections  1024;
}

http {
    include       mime.types;
    default_type  application/octet-stream;
    sendfile        on;
    keepalive_timeout  65;

$content
}
"@
    
    # Write the configuration
    Set-Content -Path $nginxConf -Value $windowsNginxConf -Encoding UTF8
    Write-Status "Nginx configuration updated"
    return $true
}

function Start-PHPFastCGI {
    Write-Status "Starting PHP FastCGI..."
    
    # Kill any existing php-cgi processes
    Get-Process -Name "php-cgi" -ErrorAction SilentlyContinue | Stop-Process -Force
    
    # Start PHP FastCGI
    $phpCgiPath = "$PHPPath\php-cgi.exe"
    $startInfo = New-Object System.Diagnostics.ProcessStartInfo
    $startInfo.FileName = $phpCgiPath
    $startInfo.Arguments = "-b 127.0.0.1:9000"
    $startInfo.WindowStyle = [System.Diagnostics.ProcessWindowStyle]::Hidden
    $startInfo.CreateNoWindow = $true
    
    try {
        $process = [System.Diagnostics.Process]::Start($startInfo)
        Write-Status "PHP FastCGI started on port 9000"
        return $true
    }
    catch {
        Write-Error-Custom "Failed to start PHP FastCGI: $($_.Exception.Message)"
        return $false
    }
}

function Start-NginxService {
    Write-Status "Starting Nginx..."
    
    Push-Location $NginxPath
    
    try {
        # Test configuration
        $testResult = & ".\nginx.exe" -t 2>&1
        if ($LASTEXITCODE -ne 0) {
            Write-Error-Custom "Nginx configuration test failed: $testResult"
            return $false
        }
        
        Write-Status "Nginx configuration test passed"
        
        # Start Nginx
        Start-Process -FilePath ".\nginx.exe" -WindowStyle Hidden
        Write-Status "Nginx started"
        return $true
    }
    catch {
        Write-Error-Custom "Failed to start Nginx: $($_.Exception.Message)"
        return $false
    }
    finally {
        Pop-Location
    }
}

function Test-Deployment {
    Write-Status "Testing deployment..."
    
    Start-Sleep -Seconds 3
    
    try {
        # Test basic connectivity
        $response = Invoke-WebRequest -Uri "http://localhost" -UseBasicParsing -TimeoutSec 10
        if ($response.StatusCode -eq 200) {
            Write-Status "✅ Basic connectivity test: PASSED"
        } else {
            Write-Warning-Custom "❌ Basic connectivity test: FAILED (Status: $($response.StatusCode))"
        }
    }
    catch {
        Write-Warning-Custom "❌ Basic connectivity test: FAILED ($($_.Exception.Message))"
    }
    
    try {
        # Test static files
        $response = Invoke-WebRequest -Uri "http://localhost/css/style.css" -UseBasicParsing -TimeoutSec 10
        if ($response.StatusCode -eq 200) {
            Write-Status "✅ Static files test: PASSED"
        } else {
            Write-Warning-Custom "❌ Static files test: FAILED"
        }
    }
    catch {
        Write-Warning-Custom "❌ Static files test: FAILED ($($_.Exception.Message))"
    }
}

function Create-StartupScripts {
    Write-Status "Creating startup scripts..."
    
    # PHP FastCGI startup script
    $phpStartScript = @"
@echo off
echo Starting PHP FastCGI...
cd /d "$PHPPath"
start /b php-cgi.exe -b 127.0.0.1:9000
echo PHP FastCGI started on port 9000
"@
    
    Set-Content -Path "$ProjectPath\start-php.bat" -Value $phpStartScript
    
    # Nginx startup script
    $nginxStartScript = @"
@echo off
echo Starting Nginx...
cd /d "$NginxPath"
start /b nginx.exe
echo Nginx started
"@
    
    Set-Content -Path "$ProjectPath\start-nginx.bat" -Value $nginxStartScript
    
    # Combined startup script
    $combinedScript = @"
@echo off
echo Starting E-Commerce Application...
call "$ProjectPath\start-php.bat"
timeout /t 2 /nobreak >nul
call "$ProjectPath\start-nginx.bat"
echo.
echo E-Commerce application started!
echo Visit: http://localhost
pause
"@
    
    Set-Content -Path "$ProjectPath\start-ecommerce.bat" -Value $combinedScript
    
    Write-Status "Startup scripts created in project directory"
}

# Main deployment function
function Main {
    Write-Host ""
    Write-Status "🚀 Starting E-Commerce Nginx deployment for Windows..."
    Write-Host ""
    
    # Check administrator privileges
    if (!(Test-Administrator)) {
        Write-Error-Custom "Please run this script as Administrator"
        exit 1
    }
    
    # Install/Check Nginx
    if (!(Install-Nginx)) {
        exit 1
    }
    
    # Install/Check PHP
    if (!(Install-PHP)) {
        exit 1
    }
    
    # Stop Apache
    Stop-ApacheService
    
    # Configure Nginx
    if (!(Configure-Nginx)) {
        exit 1
    }
    
    # Start PHP FastCGI
    if (!(Start-PHPFastCGI)) {
        exit 1
    }
    
    # Start Nginx
    if (!(Start-NginxService)) {
        exit 1
    }
    
    # Test deployment
    Test-Deployment
    
    # Create startup scripts
    Create-StartupScripts
    
    Write-Host ""
    Write-Status "🎉 Deployment completed successfully!"
    Write-Status "Your E-Commerce application is now running on Nginx"
    Write-Status "Visit: http://localhost"
    Write-Host ""
    Write-Warning-Custom "Important notes:"
    Write-Warning-Custom "1. Use start-ecommerce.bat to start both PHP and Nginx"
    Write-Warning-Custom "2. Configure your database connection if needed"
    Write-Warning-Custom "3. Test all functionality thoroughly"
    Write-Warning-Custom "4. For production, configure SSL and domain settings"
    Write-Host ""
}

# Run main function
Main
