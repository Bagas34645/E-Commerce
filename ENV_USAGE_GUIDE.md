# Environment Files Usage Guide

## 🤔 Kapan Menggunakan File .env Mana?

### 📁 **File Environment yang Tersedia:**

```
E-Commerce/
├── .env.example              # ✅ Untuk semua deployment
├── .env.nginx.example        # 🚀 Khusus konfigurasi Nginx
├── .env.nginx.full.example   # 🎯 Lengkap: App + Nginx
└── .env (your actual config) # 🔒 File aktual (di-ignore Git)
```

---

## 🎯 **Scenario Penggunaan:**

### 1. **Shared Hosting (cPanel/Plesk)**

**Gunakan:** `.env.example`

```bash
cp .env.example .env
```

**Alasan:**

- ✅ Menggunakan Apache (.htaccess)
- ✅ Tidak perlu konfigurasi server
- ✅ Fokus pada aplikasi saja

### 2. **VPS dengan Apache**

**Gunakan:** `.env.example`

```bash
cp .env.example .env
```

**Konfigurasi:**

```bash
APP_ENV=production
APP_DEBUG=false
BASE_URL=https://yourdomain.com
```

### 3. **VPS dengan Nginx (Basic)**

**Gunakan:** `.env.example` + manual Nginx config

```bash
cp .env.example .env
# + copy nginx.conf manually
```

### 4. **VPS dengan Nginx (Advanced)**

**Gunakan:** `.env.nginx.full.example`

```bash
cp .env.nginx.full.example .env
```

**Keuntungan:**

- ✅ Satu file untuk semua konfigurasi
- ✅ Application + server settings
- ✅ SSL, performance, security

---

## 📋 **Decision Matrix:**

| Hosting Type   | Web Server | Recommended File          | Complexity  |
| -------------- | ---------- | ------------------------- | ----------- |
| Shared Hosting | Apache     | `.env.example`            | 🟢 Easy     |
| VPS/Cloud      | Apache     | `.env.example`            | 🟡 Medium   |
| VPS/Cloud      | Nginx      | `.env.nginx.full.example` | 🔴 Advanced |
| Docker         | Nginx      | `.env.nginx.full.example` | 🔴 Advanced |

---

## 🛠️ **Setup Instructions:**

### **Option A: Simple Deployment (Apache/Shared)**

```bash
# 1. Copy basic template
cp .env.example .env

# 2. Edit application settings
nano .env

# 3. Update these values:
APP_ENV=production
APP_DEBUG=false
DB_HOST=your-db-host
DB_PASS=your-db-password
BASE_URL=https://yourdomain.com
```

### **Option B: Advanced Nginx Deployment**

```bash
# 1. Copy full template
cp .env.nginx.full.example .env

# 2. Edit application settings
nano .env

# 3. Update application values:
APP_ENV=production
DB_HOST=your-db-host
DB_PASS=your-db-password
BASE_URL=https://yourdomain.com

# 4. Update server values:
PHP_VERSION=8.1
SSL_CERTIFICATE_PATH=/path/to/your/cert.crt
NGINX_WORKER_PROCESSES=4
```

### **Option C: Hybrid (App config only)**

```bash
# 1. Use basic .env for app
cp .env.example .env

# 2. Use nginx.conf for server
cp nginx.conf /etc/nginx/sites-available/ecommerce

# 3. Manual server configuration
```

---

## 🔧 **Configuration Loader Update:**

Update `config/env.php` to handle both approaches:

```php
// Load .env file
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    loadEnvFile($envFile);
}

// Auto-detect server type
if (!env('SERVER_TYPE')) {
    // Detect if running on Nginx
    if (isset($_SERVER['SERVER_SOFTWARE']) &&
        stripos($_SERVER['SERVER_SOFTWARE'], 'nginx') !== false) {
        putenv('SERVER_TYPE=nginx');
    } else {
        putenv('SERVER_TYPE=apache');
    }
}
```

---

## 🚀 **Migration Path:**

### **From Apache to Nginx:**

```bash
# 1. Backup current .env
cp .env .env.apache.backup

# 2. Extend with Nginx settings
cat .env.nginx.example >> .env

# 3. Configure Nginx-specific values
# 4. Deploy nginx.conf
# 5. Test functionality
```

### **From Basic to Advanced:**

```bash
# 1. Backup current
cp .env .env.basic.backup

# 2. Use full template
cp .env.nginx.full.example .env

# 3. Migrate old values
# 4. Configure new features
```

---

## 📊 **Feature Comparison:**

| Feature           | .env.example | .env.nginx.full.example |
| ----------------- | ------------ | ----------------------- |
| **App Config**    | ✅ Complete  | ✅ Complete             |
| **DB Config**     | ✅ Basic     | ✅ Advanced             |
| **Server Config** | ❌ None      | ✅ Complete             |
| **SSL Config**    | ❌ None      | ✅ Complete             |
| **Performance**   | ❌ None      | ✅ Tuning               |
| **Security**      | ✅ Basic     | ✅ Advanced             |
| **Monitoring**    | ❌ None      | ✅ Logging              |
| **Backup**        | ❌ None      | ✅ Config               |

---

## 🎯 **Recommendation:**

### **For Beginners:**

```bash
cp .env.example .env
```

- ✅ Simple setup
- ✅ Works with Apache/Nginx
- ✅ Focus on application

### **For Production/Advanced:**

```bash
cp .env.nginx.full.example .env
```

- ✅ Complete control
- ✅ Production-ready
- ✅ Performance optimized
- ✅ Security hardened

### **Quick Start Command:**

```bash
# Detect environment and copy appropriate template
if command -v nginx &> /dev/null; then
    echo "Nginx detected, using full template..."
    cp .env.nginx.full.example .env
else
    echo "Using basic template..."
    cp .env.example .env
fi
```

Choose the file that matches your deployment complexity and infrastructure! 🎉
