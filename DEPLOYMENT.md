# Sentra Durian Tegal - E-Commerce System

## 🚀 Deployment Guide

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- **Web Server**: Apache with mod_rewrite OR Nginx with PHP-FPM
- MySQL PDO extension

### Web Server Options

#### Option 1: Apache (Current Setup)

- Uses `.htaccess` for URL rewriting and security
- Simpler setup for shared hosting
- See existing documentation below

#### Option 2: Nginx (Recommended for VPS/Dedicated)

- Better performance and resource usage
- Centralized configuration
- See `NGINX_DEPLOYMENT.md` for full setup guide
- See `APACHE_TO_NGINX_MIGRATION.md` for migration guide

### Installation Steps

1. **Upload Files**

   ```bash
   # Upload all files to your web server root directory
   # For shared hosting: public_html/
   # For VPS: /var/www/html/
   ```

2. **Database Setup**

   ```bash
   # Import the database
   mysql -u username -p database_name < food_db.sql
   ```

3. **Configuration**

   ```bash
   # Copy environment file
   cp .env.example .env

   # Edit .env file with your database credentials
   nano .env
   ```

4. **Permissions**

   ```bash
   # Set proper permissions for upload directory
   chmod 755 uploaded_img/
   chmod 644 config/config.php
   ```

5. **Apache Configuration**
   - Ensure mod_rewrite is enabled
   - The .htaccess file is already configured

### Production Configuration

1. **Update config/config.php**:

   ```php
   define('APP_ENV', 'production');
   define('APP_DEBUG', false);
   define('BASE_URL', 'https://yourdomain.com');
   ```

2. **Security Checklist**:
   - [ ] Change default database credentials
   - [ ] Set strong passwords for admin accounts
   - [ ] Configure SSL certificate
   - [ ] Enable firewall rules
   - [ ] Regular database backups

### URL Structure

- **Home**: `/`
- **Products**: `/menu`
- **Cart**: `/cart`
- **About**: `/about`
- **Contact**: `/contact`
- **Orders**: `/orders`
- **Search**: `/search`

### File Structure

```
E-Commerce/
├── app/                    # MVC Application
│   ├── controllers/        # Controller classes
│   ├── models/            # Model classes
│   └── views/             # View templates
├── components/            # Legacy components
├── config/               # Configuration files
├── core/                 # MVC Framework core
├── css/                  # Stylesheets
├── images/               # Static images
├── js/                   # JavaScript files
├── uploaded_img/         # User uploaded images
├── .htaccess            # Apache configuration
└── index.php            # Main entry point
```

### Maintenance

1. **Log Files**: Check server error logs regularly
2. **Database**: Regular backups and optimization
3. **Updates**: Keep PHP and MySQL updated
4. **Monitoring**: Monitor disk space and performance

### Support

For technical support, contact the development team.

---

**Version**: 1.0.0  
**Last Updated**: July 2025
