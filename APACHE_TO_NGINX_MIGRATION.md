# Migration Guide: Apache to Nginx

## 🔄 Converting E-Commerce from Apache to Nginx

### Key Differences

| Feature       | Apache (.htaccess)      | Nginx (server block)             |
| ------------- | ----------------------- | -------------------------------- |
| Configuration | Distributed (.htaccess) | Centralized (server block)       |
| URL Rewriting | mod_rewrite             | try_files directive              |
| Security      | Files/Directory blocks  | location blocks                  |
| Performance   | Good                    | Better (especially static files) |

### 1. URL Rewriting Conversion

**Apache (.htaccess):**

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . index.php [L]
```

**Nginx Equivalent:**

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 2. Security Rules Conversion

**Apache (.htaccess):**

```apache
<Files ~ "^\.">
    Require all denied
</Files>

<Files ~ "\.md$">
    Require all denied
</Files>
```

**Nginx Equivalent:**

```nginx
location ~ /\. {
    deny all;
}

location ~* \.(md|sql|log)$ {
    deny all;
}
```

### 3. Static Files & Caching

**Apache (.htaccess):**

```apache
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

**Nginx Equivalent:**

```nginx
location ~* \.(css|js|png|jpg|jpeg|gif)$ {
    expires 1M;
    add_header Cache-Control "public, immutable";
}
```

### 4. Directory Access Control

**Apache (.htaccess):**

```apache
<Directory "/path/to/admin">
    Require ip 192.168.1.0/24
</Directory>
```

**Nginx Equivalent:**

```nginx
location /admin/ {
    allow 192.168.1.0/24;
    deny all;
}
```

### 5. PHP Processing

**Apache (built-in mod_php):**

- PHP runs as Apache module
- Automatic PHP file processing

**Nginx (requires PHP-FPM):**

```nginx
location ~ \.php$ {
    include snippets/fastcgi-php.conf;
    fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
}
```

### Migration Steps

#### Step 1: Backup Current Setup

```bash
# Backup Apache configuration
sudo cp /etc/apache2/sites-available/000-default.conf ~/apache-backup.conf

# Backup .htaccess
cp /var/www/html/E-Commerce/.htaccess ~/htaccess-backup
```

#### Step 2: Install Nginx and PHP-FPM

```bash
# Stop Apache
sudo systemctl stop apache2
sudo systemctl disable apache2

# Install Nginx and PHP-FPM
sudo apt install nginx php8.1-fpm

# Start services
sudo systemctl start nginx
sudo systemctl start php8.1-fpm
sudo systemctl enable nginx
sudo systemctl enable php8.1-fpm
```

#### Step 3: Configure Nginx

```bash
# Copy nginx configuration
sudo cp /var/www/html/E-Commerce/nginx.conf /etc/nginx/sites-available/ecommerce

# Enable site
sudo ln -s /etc/nginx/sites-available/ecommerce /etc/nginx/sites-enabled/

# Remove default
sudo rm /etc/nginx/sites-enabled/default

# Test configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

#### Step 4: Test Functionality

1. **Basic Access Test:**

   ```bash
   curl -I http://your-domain.com
   ```

2. **PHP Processing Test:**

   ```bash
   curl http://your-domain.com/index.php
   ```

3. **Clean URL Test:**

   ```bash
   curl http://your-domain.com/about
   ```

4. **Static Files Test:**
   ```bash
   curl -I http://your-domain.com/css/style.css
   ```

### Performance Comparison

#### Before (Apache)

```bash
ab -n 1000 -c 10 http://your-domain.com/
```

#### After (Nginx)

```bash
ab -n 1000 -c 10 http://your-domain.com/
```

**Expected Improvements:**

- 20-30% faster static file serving
- Lower memory usage
- Better concurrent connection handling
- Improved security (centralized config)

### Troubleshooting Common Issues

#### 1. 502 Bad Gateway

**Cause:** PHP-FPM not running or wrong socket path
**Solution:**

```bash
sudo systemctl status php8.1-fpm
sudo systemctl restart php8.1-fpm
```

#### 2. Clean URLs Not Working

**Cause:** Missing try_files directive
**Solution:** Ensure nginx.conf has correct try_files configuration

#### 3. File Upload Issues

**Cause:** Different upload limits
**Solution:** Update both nginx.conf and php.ini:

```nginx
client_max_body_size 50M;
```

```ini
upload_max_filesize = 50M
post_max_size = 50M
```

#### 4. Permission Issues

**Cause:** Different user/group
**Solution:**

```bash
sudo chown -R www-data:www-data /var/www/html/E-Commerce
sudo chmod -R 755 /var/www/html/E-Commerce
sudo chmod -R 777 /var/www/html/E-Commerce/uploaded_img
```

### Configuration Validation

Create a simple test script to validate all functionality:

```bash
#!/bin/bash
# test-migration.sh

echo "Testing E-Commerce Migration to Nginx..."

# Test home page
echo "1. Testing home page..."
curl -s -o /dev/null -w "%{http_code}" http://localhost/ || echo "FAIL"

# Test clean URLs
echo "2. Testing clean URLs..."
curl -s -o /dev/null -w "%{http_code}" http://localhost/about || echo "FAIL"

# Test static files
echo "3. Testing static files..."
curl -s -o /dev/null -w "%{http_code}" http://localhost/css/style.css || echo "FAIL"

# Test PHP processing
echo "4. Testing PHP processing..."
curl -s http://localhost/index.php | grep -q "<!DOCTYPE" && echo "PASS" || echo "FAIL"

# Test admin protection
echo "5. Testing admin protection..."
curl -s -o /dev/null -w "%{http_code}" http://localhost/config/config.php | grep -q "403" && echo "PASS" || echo "FAIL"

echo "Migration test completed!"
```

### Final Checklist

- [ ] Nginx installed and running
- [ ] PHP-FPM installed and running
- [ ] Database connection working
- [ ] Clean URLs working
- [ ] Static files serving correctly
- [ ] File uploads working
- [ ] Admin panel accessible
- [ ] Security rules in place
- [ ] SSL certificate configured (production)
- [ ] Performance monitoring setup

Your E-Commerce application is now successfully migrated to Nginx! 🎉
