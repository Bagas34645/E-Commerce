#!/bin/bash

# E-Commerce Nginx Deployment Script
# This script helps deploy the E-Commerce application to Nginx

echo "🚀 E-Commerce Nginx Deployment Script"
echo "======================================"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration variables
PROJECT_PATH="/var/www/html/E-Commerce"
NGINX_SITES_AVAILABLE="/etc/nginx/sites-available"
NGINX_SITES_ENABLED="/etc/nginx/sites-enabled"
SITE_NAME="ecommerce"

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if running as root
check_root() {
    if [ "$EUID" -ne 0 ]; then
        print_error "Please run this script as root (use sudo)"
        exit 1
    fi
}

# Install required packages
install_packages() {
    print_status "Installing required packages..."
    
    # Detect OS
    if [ -f /etc/debian_version ]; then
        # Debian/Ubuntu
        apt update
        apt install -y nginx php8.1-fpm php8.1-mysql php8.1-xml php8.1-curl php8.1-mbstring php8.1-gd php8.1-zip
    elif [ -f /etc/redhat-release ]; then
        # CentOS/RHEL
        yum install -y epel-release
        yum install -y nginx php-fpm php-mysql php-xml php-curl php-mbstring php-gd php-zip
    else
        print_error "Unsupported OS. Please install nginx and php-fpm manually."
        exit 1
    fi
}

# Stop Apache if running
stop_apache() {
    print_status "Stopping Apache if running..."
    
    if systemctl is-active --quiet apache2; then
        systemctl stop apache2
        systemctl disable apache2
        print_status "Apache stopped and disabled"
    elif systemctl is-active --quiet httpd; then
        systemctl stop httpd
        systemctl disable httpd
        print_status "Apache (httpd) stopped and disabled"
    else
        print_warning "Apache not running or not found"
    fi
}

# Configure Nginx
configure_nginx() {
    print_status "Configuring Nginx..."
    
    # Copy nginx configuration
    if [ -f "$PROJECT_PATH/nginx.conf" ]; then
        cp "$PROJECT_PATH/nginx.conf" "$NGINX_SITES_AVAILABLE/$SITE_NAME"
        print_status "Nginx configuration copied"
    else
        print_error "nginx.conf not found in project directory"
        exit 1
    fi
    
    # Enable site
    if [ -f "$NGINX_SITES_ENABLED/default" ]; then
        rm "$NGINX_SITES_ENABLED/default"
        print_status "Default site disabled"
    fi
    
    ln -sf "$NGINX_SITES_AVAILABLE/$SITE_NAME" "$NGINX_SITES_ENABLED/"
    print_status "Site enabled"
}

# Set permissions
set_permissions() {
    print_status "Setting file permissions..."
    
    # Set ownership
    chown -R www-data:www-data "$PROJECT_PATH"
    
    # Set directory permissions
    find "$PROJECT_PATH" -type d -exec chmod 755 {} \;
    
    # Set file permissions
    find "$PROJECT_PATH" -type f -exec chmod 644 {} \;
    
    # Special permissions for uploads
    if [ -d "$PROJECT_PATH/uploaded_img" ]; then
        chmod 777 "$PROJECT_PATH/uploaded_img"
        print_status "Upload directory permissions set"
    fi
    
    print_status "Permissions set successfully"
}

# Test Nginx configuration
test_nginx() {
    print_status "Testing Nginx configuration..."
    
    if nginx -t; then
        print_status "Nginx configuration is valid"
        return 0
    else
        print_error "Nginx configuration has errors"
        return 1
    fi
}

# Start services
start_services() {
    print_status "Starting services..."
    
    # Start PHP-FPM
    systemctl start php8.1-fpm || systemctl start php-fpm
    systemctl enable php8.1-fpm || systemctl enable php-fpm
    
    # Start Nginx
    systemctl start nginx
    systemctl enable nginx
    
    print_status "Services started and enabled"
}

# Test deployment
test_deployment() {
    print_status "Testing deployment..."
    
    # Test basic connectivity
    if curl -s -o /dev/null -w "%{http_code}" http://localhost/ | grep -q "200"; then
        print_status "✅ Basic connectivity test: PASSED"
    else
        print_warning "❌ Basic connectivity test: FAILED"
    fi
    
    # Test PHP processing
    if curl -s http://localhost/index.php | grep -q "<!DOCTYPE"; then
        print_status "✅ PHP processing test: PASSED"
    else
        print_warning "❌ PHP processing test: FAILED"
    fi
    
    # Test static files
    if curl -s -o /dev/null -w "%{http_code}" http://localhost/css/style.css | grep -q "200"; then
        print_status "✅ Static files test: PASSED"
    else
        print_warning "❌ Static files test: FAILED"
    fi
}

# Main deployment function
main() {
    echo
    print_status "Starting E-Commerce Nginx deployment..."
    echo
    
    # Check if running as root
    check_root
    
    # Install packages
    install_packages
    
    # Stop Apache
    stop_apache
    
    # Configure Nginx
    configure_nginx
    
    # Set permissions
    set_permissions
    
    # Test configuration
    if test_nginx; then
        # Start services
        start_services
        
        # Test deployment
        echo
        test_deployment
        
        echo
        print_status "🎉 Deployment completed successfully!"
        print_status "Your E-Commerce application is now running on Nginx"
        print_status "Visit: http://localhost or http://your-domain.com"
        echo
        print_warning "Don't forget to:"
        print_warning "1. Update your domain in /etc/nginx/sites-available/$SITE_NAME"
        print_warning "2. Configure SSL certificate for production"
        print_warning "3. Update database configuration if needed"
        print_warning "4. Test all functionality thoroughly"
    else
        print_error "Deployment failed due to configuration errors"
        exit 1
    fi
}

# Run main function
main "$@"
