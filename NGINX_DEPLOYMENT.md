# Nginx Deployment Guide for E-Commerce Project

## 🚀 Setting Up Nginx for E-Commerce

### Prerequisites

- Ubuntu/Debian or CentOS/RHEL server
- Nginx installed
- PHP-FPM installed (PHP 7.4+ recommended)
- MySQL/MariaDB installed

### Installation Steps

#### 1. Install Required Packages

**Ubuntu/Debian:**

```bash
sudo apt update
sudo apt install nginx php8.1-fpm php8.1-mysql php8.1-xml php8.1-curl php8.1-mbstring mysql-server
```

**CentOS/RHEL:**

```bash
sudo yum install epel-release
sudo yum install nginx php-fpm php-mysql php-xml php-curl php-mbstring mariadb-server
```

#### 2. Configure PHP-FPM

Edit PHP-FPM configuration:

```bash
sudo nano /etc/php/8.1/fpm/pool.d/www.conf
```

Key settings to verify:

```ini
user = www-data
group = www-data
listen = /var/run/php/php8.1-fpm.sock
listen.owner = www-data
listen.group = www-data
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
```

#### 3. Deploy Application Files

```bash
# Create web directory
sudo mkdir -p /var/www/html/E-Commerce

# Copy project files
sudo cp -r /path/to/your/E-Commerce/* /var/www/html/E-Commerce/

# Set proper permissions
sudo chown -R www-data:www-data /var/www/html/E-Commerce
sudo chmod -R 755 /var/www/html/E-Commerce
sudo chmod -R 777 /var/www/html/E-Commerce/uploaded_img
```

#### 4. Configure Nginx

Copy the Nginx configuration:

```bash
sudo cp /var/www/html/E-Commerce/nginx.conf /etc/nginx/sites-available/ecommerce
sudo ln -s /etc/nginx/sites-available/ecommerce /etc/nginx/sites-enabled/
```

Remove default site:

```bash
sudo rm /etc/nginx/sites-enabled/default
```

Test Nginx configuration:

```bash
sudo nginx -t
```

#### 5. Configure Database

```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Import database
mysql -u root -p
CREATE DATABASE food_db;
USE food_db;
SOURCE /var/www/html/E-Commerce/food_db.sql;
EXIT;
```

#### 6. Update Application Configuration

Edit the configuration file:

```bash
sudo nano /var/www/html/E-Commerce/config/config.php
```

Update these values:

```php
define('APP_ENV', 'production');
define('APP_DEBUG', false);
define('BASE_URL', 'https://yourdomain.com');
define('DB_HOST', 'localhost');
define('DB_NAME', 'food_db');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_secure_password');
```

#### 7. Start Services

```bash
# Start and enable services
sudo systemctl start nginx
sudo systemctl enable nginx
sudo systemctl start php8.1-fpm
sudo systemctl enable php8.1-fpm
sudo systemctl start mysql
sudo systemctl enable mysql

# Check status
sudo systemctl status nginx
sudo systemctl status php8.1-fpm
sudo systemctl status mysql
```

### Security Considerations

#### 1. Firewall Configuration

```bash
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

#### 2. SSL Certificate (Production)

Using Let's Encrypt:

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

#### 3. Additional Security Headers

The nginx.conf already includes security headers, but you can add more:

```nginx
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
add_header Permissions-Policy "geolocation=(), microphone=(), camera=()";
```

### Performance Tuning

#### 1. PHP-FPM Optimization

Edit `/etc/php/8.1/fpm/php.ini`:

```ini
memory_limit = 256M
post_max_size = 50M
upload_max_filesize = 50M
max_execution_time = 300
max_input_vars = 3000
```

#### 2. Nginx Worker Processes

Edit `/etc/nginx/nginx.conf`:

```nginx
worker_processes auto;
worker_connections 1024;
keepalive_timeout 65;
client_max_body_size 50M;
```

#### 3. Enable HTTP/2 (with SSL)

```nginx
listen 443 ssl http2;
```

### Monitoring and Maintenance

#### 1. Log Files

- Nginx Access: `/var/log/nginx/ecommerce_access.log`
- Nginx Error: `/var/log/nginx/ecommerce_error.log`
- PHP-FPM: `/var/log/php8.1-fpm.log`

#### 2. Log Rotation

Create `/etc/logrotate.d/ecommerce`:

```
/var/log/nginx/ecommerce_*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 0644 www-data adm
    postrotate
        systemctl reload nginx
    endscript
}
```

#### 3. Backup Script

```bash
#!/bin/bash
# backup.sh
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u root -p food_db > /backup/food_db_$DATE.sql
tar -czf /backup/ecommerce_files_$DATE.tar.gz /var/www/html/E-Commerce
```

### Troubleshooting

#### Common Issues:

1. **502 Bad Gateway**

   - Check PHP-FPM status: `sudo systemctl status php8.1-fpm`
   - Check socket path in nginx config

2. **File Upload Issues**

   - Check `upload_max_filesize` in php.ini
   - Check `client_max_body_size` in nginx.conf
   - Verify directory permissions

3. **Database Connection Issues**
   - Verify MySQL service is running
   - Check database credentials in config.php
   - Test connection: `mysql -u username -p`

### Testing the Setup

1. **Basic Test:**

   ```bash
   curl -I http://yourdomain.com
   ```

2. **PHP Test:**
   Create `/var/www/html/E-Commerce/test.php`:

   ```php
   <?php phpinfo(); ?>
   ```

   Visit: `http://yourdomain.com/test.php`
   (Remove after testing!)

3. **Database Test:**
   ```bash
   mysql -u your_db_user -p food_db -e "SHOW TABLES;"
   ```

### Performance Benchmarking

Test with Apache Bench:

```bash
ab -n 100 -c 10 http://yourdomain.com/
```

Your E-Commerce application is now ready to run on Nginx! 🎉
