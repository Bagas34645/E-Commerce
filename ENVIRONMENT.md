# Environment Configuration Guide

## 🌍 Mengapa Menggunakan .env?

### Masalah Tanpa .env:

```php
// ❌ BURUK: Hardcode sensitive data
define('DB_HOST', 'production-server.com');
define('DB_PASS', 'secret123!@#');
define('API_KEY', 'sk_live_abc123xyz789');
```

**Masalah:**

- Password terlihat di Git history
- Sulit ganti konfigurasi antar environment
- Risk security jika repository public
- Tim developer punya config berbeda

### Solusi dengan .env:

```php
// ✅ BAIK: Environment-based configuration
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_PASS', env('DB_PASS', ''));
define('API_KEY', env('API_KEY', ''));
```

**Keuntungan:**

- ✅ Sensitive data tidak masuk Git
- ✅ Easy switching between environments
- ✅ Secure by default
- ✅ Standard industry practice

## 📁 File Structure

```
E-Commerce/
├── .env                    # Real config (ignored by Git)
├── .env.example           # Template (committed to Git)
├── .env.nginx.example     # Nginx-specific template
├── .gitignore            # Protects .env from Git
└── config/
    ├── env.php           # Environment loader
    └── config.php        # Main config (uses .env)
```

## 🚀 How to Use

### 1. Setup for Development:

```bash
# Copy template
cp .env.example .env

# Edit with your local settings
nano .env
```

### 2. Setup for Production:

```bash
# Copy template
cp .env.example .env

# Configure production values
APP_ENV=production
APP_DEBUG=false
DB_HOST=your-production-host
DB_PASS=your-secure-password
BASE_URL=https://yourdomain.com
```

### 3. Team Development:

```bash
# Each developer has their own .env
# Alice's .env
DB_NAME=food_db_alice
BASE_URL=http://alice.localhost

# Bob's .env
DB_NAME=food_db_bob
BASE_URL=http://bob.localhost
```

## 🔧 Configuration Options

### Database Settings:

```bash
DB_HOST=localhost          # Database server
DB_NAME=food_db           # Database name
DB_USER=root              # Database username
DB_PASS=                  # Database password (empty for local)
DB_CHARSET=utf8mb4        # Character set
```

### Application Settings:

```bash
APP_ENV=development       # development|production
APP_DEBUG=true           # true|false
APP_NAME="Sentra Durian Tegal"
BASE_URL=http://localhost/E-Commerce
```

### Security Settings:

```bash
SESSION_TIMEOUT=3600      # Session timeout in seconds
MAX_UPLOAD_SIZE=10M       # Maximum file upload size
```

### Email Settings (Optional):

```bash
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
```

## 🛡️ Security Best Practices

### 1. Never Commit .env:

```bash
# .gitignore
.env
.env.local
.env.production
```

### 2. Use Strong Passwords:

```bash
# ❌ Weak
DB_PASS=123456

# ✅ Strong
DB_PASS=X9#mK$2vB@8qN!pL
```

### 3. Different Keys per Environment:

```bash
# Development
API_KEY=sk_test_123abc

# Production
API_KEY=sk_live_xyz789
```

## 🔄 Environment Types

### Development (.env):

```bash
APP_ENV=development
APP_DEBUG=true
DB_HOST=localhost
DB_NAME=food_db_dev
BASE_URL=http://localhost/E-Commerce
```

### Staging (.env):

```bash
APP_ENV=staging
APP_DEBUG=true
DB_HOST=staging-server.com
DB_NAME=food_db_staging
BASE_URL=https://staging.yourdomain.com
```

### Production (.env):

```bash
APP_ENV=production
APP_DEBUG=false
DB_HOST=production-server.com
DB_NAME=food_db_prod
BASE_URL=https://yourdomain.com
```

## 🧪 Testing Configuration

Create test script to validate .env loading:

```php
<?php
// test-env.php
require_once 'config/config.php';

echo "Testing Environment Configuration:\n";
echo "APP_ENV: " . APP_ENV . "\n";
echo "APP_DEBUG: " . (APP_DEBUG ? 'true' : 'false') . "\n";
echo "DB_HOST: " . DB_HOST . "\n";
echo "DB_NAME: " . DB_NAME . "\n";
echo "BASE_URL: " . BASE_URL . "\n";
echo "APP_NAME: " . APP_NAME . "\n";

// Test database connection
try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    echo "Database: Connected ✅\n";
} catch (PDOException $e) {
    echo "Database: Failed ❌\n";
}
?>
```

## 🚨 Troubleshooting

### Problem: .env not loading

**Solution:** Check file permissions and path

```bash
chmod 644 .env
ls -la .env
```

### Problem: Boolean values not working

**Solution:** Use string format

```bash
# ❌ Wrong
APP_DEBUG=1

# ✅ Correct
APP_DEBUG=true
```

### Problem: Quotes in values

**Solution:** Use proper quoting

```bash
# For values with spaces
APP_NAME="Sentra Durian Tegal"

# For values with special chars
DB_PASS="p@ssw0rd#123"
```

## 📋 Deployment Checklist

### Before Deploy:

- [ ] Copy .env.example to .env
- [ ] Update all production values
- [ ] Set APP_ENV=production
- [ ] Set APP_DEBUG=false
- [ ] Use strong database password
- [ ] Update BASE_URL to production domain
- [ ] Test database connection
- [ ] Verify file permissions

### Security Check:

- [ ] .env file not accessible via web
- [ ] .gitignore includes .env
- [ ] No sensitive data in .env.example
- [ ] Strong passwords used
- [ ] SSL certificate configured

Your environment configuration is now production-ready! 🎉
