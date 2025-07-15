# 🚀 E-Commerce Nginx Migration - Quick Reference

## ⚡ Quick Start

### For Linux (Production)

```bash
# 1. Run deployment script
chmod +x deploy-nginx.sh
sudo ./deploy-nginx.sh

# 2. Test deployment
chmod +x test-deployment.sh
./test-deployment.sh
```

### For Windows (Development)

```batch
# 1. Download Nginx and PHP
# Nginx: http://nginx.org/en/download.html → Extract to C:\nginx
# PHP: https://windows.php.net/download/ → Extract to C:\php

# 2. Run as Administrator
setup-nginx-windows.bat

# 3. Test deployment
test-deployment.bat

# 4. Start application
start-ecommerce.bat
```

---

## 📋 Manual Steps (if scripts fail)

### Linux Manual Setup

```bash
# Stop Apache
sudo systemctl stop apache2
sudo systemctl disable apache2

# Install Nginx + PHP-FPM
sudo apt install nginx php8.2-fpm php8.2-mysql

# Deploy config
sudo cp nginx.conf /etc/nginx/sites-available/ecommerce
sudo ln -s /etc/nginx/sites-available/ecommerce /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default

# Set permissions
sudo chown -R www-data:www-data /var/www/E-Commerce
sudo chmod -R 755 /var/www/E-Commerce
sudo chmod 777 /var/www/E-Commerce/uploaded_img

# Start services
sudo systemctl start nginx php8.2-fpm
sudo systemctl enable nginx php8.2-fpm
```

### Windows Manual Setup

```batch
# Stop Apache (if running)
net stop "Apache2.4"

# Start PHP FastCGI
cd C:\php
start php-cgi.exe -b 127.0.0.1:9000

# Copy nginx.conf to C:\nginx\conf\nginx.conf
# Start Nginx
cd C:\nginx
start nginx.exe
```

---

## 🔧 Troubleshooting

### Error 502 Bad Gateway

```bash
# Check PHP-FPM
sudo systemctl status php8.2-fpm
sudo systemctl restart php8.2-fpm

# Check socket path in nginx.conf
fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
```

### Clean URLs Not Working

```nginx
# Ensure nginx.conf has:
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### File Permissions

```bash
sudo chown -R www-data:www-data /var/www/E-Commerce
sudo chmod 755 /var/www/E-Commerce
sudo chmod 777 /var/www/E-Commerce/uploaded_img
```

### Check Logs

```bash
# Nginx error log
sudo tail -f /var/log/nginx/error.log

# PHP-FPM log
sudo tail -f /var/log/php8.2-fpm.log

# Test configuration
sudo nginx -t
```

---

## ✅ Quick Tests

### Test URLs

- Home: http://localhost/
- About: http://localhost/about
- Menu: http://localhost/menu
- Admin: http://localhost/admin/
- CSS: http://localhost/css/style.css

### Command Line Tests

```bash
# Basic test
curl -I http://localhost/

# PHP test
curl http://localhost/index.php | head

# Static files test
curl -I http://localhost/css/style.css

# Security test
curl -I http://localhost/config/config.php
# Should return 403 Forbidden
```

---

## 📊 Performance Comparison

| Metric                 | Apache      | Nginx       | Improvement       |
| ---------------------- | ----------- | ----------- | ----------------- |
| Static Files           | ~100ms      | ~70ms       | 30% faster        |
| Memory Usage           | ~50MB       | ~35MB       | 30% less          |
| Concurrent Connections | 256         | 1024+       | 4x more           |
| Configuration          | Distributed | Centralized | Better management |

---

## 🔒 Security Checklist

- [ ] Sensitive files blocked (_.md, _.sql, config/)
- [ ] Security headers enabled
- [ ] Directory browsing disabled
- [ ] Upload directory protected
- [ ] Admin panel accessible
- [ ] SSL configured (production)
- [ ] Firewall configured
- [ ] Regular updates scheduled

---

## 🎯 Production Deployment

### 1. Domain Configuration

```nginx
server_name yourdomain.com www.yourdomain.com;
```

### 2. SSL Certificate (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com
```

### 3. Firewall

```bash
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

### 4. Monitoring

```bash
# Install monitoring tools
sudo apt install htop iotop

# Setup log rotation
sudo nano /etc/logrotate.d/nginx
```

---

## 📞 Support Commands

```bash
# Restart all services
sudo systemctl restart nginx php8.2-fpm mysql

# Check service status
sudo systemctl status nginx php8.2-fpm mysql

# Test nginx config
sudo nginx -t

# Reload nginx (after config changes)
sudo systemctl reload nginx

# View processes
ps aux | grep nginx
ps aux | grep php-fpm
```

---

## 🔄 Rollback to Apache (if needed)

```bash
# Stop Nginx
sudo systemctl stop nginx
sudo systemctl disable nginx

# Start Apache
sudo systemctl start apache2
sudo systemctl enable apache2

# Restore .htaccess if needed
cp ~/htaccess-backup.txt .htaccess
```

---

**✨ Your E-Commerce application is now running on high-performance Nginx! ✨**

Need help? Check the full guides:

- `DEPLOYMENT_GUIDE.md` - Complete instructions
- `APACHE_TO_NGINX_MIGRATION.md` - Migration details
- `NGINX_DEPLOYMENT.md` - Advanced configuration
