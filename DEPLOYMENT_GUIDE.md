# Panduan Deployment Apache ke Nginx - E-Commerce

## 🚀 Langkah-langkah Migrasi

### Untuk Linux/Ubuntu (Recommended for Production)

#### 1. Persiapan

```bash
# Backup konfigurasi Apache
sudo cp /etc/apache2/sites-available/000-default.conf ~/apache-backup.conf
cp .htaccess ~/htaccess-backup.txt

# Install Nginx dan PHP-FPM
sudo apt update
sudo apt install nginx php8.2-fpm php8.2-mysql php8.2-curl php8.2-mbstring php8.2-xml php8.2-gd
```

#### 2. Stop Apache dan Start Nginx

```bash
sudo systemctl stop apache2
sudo systemctl disable apache2
sudo systemctl start nginx
sudo systemctl start php8.2-fpm
sudo systemctl enable nginx
sudo systemctl enable php8.2-fpm
```

#### 3. Deploy Konfigurasi

```bash
# Copy konfigurasi Nginx
sudo cp nginx.conf /etc/nginx/sites-available/ecommerce
sudo ln -s /etc/nginx/sites-available/ecommerce /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default

# Set permissions
sudo chown -R www-data:www-data /var/www/E-Commerce
sudo chmod -R 755 /var/www/E-Commerce
sudo chmod -R 777 /var/www/E-Commerce/uploaded_img

# Test dan reload
sudo nginx -t
sudo systemctl reload nginx
```

#### 4. Gunakan Script Otomatis (Alternatif)

```bash
chmod +x deploy-nginx.sh
sudo ./deploy-nginx.sh
```

---

### Untuk Windows (Development)

#### 1. Download Requirements

- **Nginx for Windows**: http://nginx.org/en/download.html
- **PHP for Windows**: https://windows.php.net/download/
- Extract Nginx ke `C:\nginx`
- Extract PHP ke `C:\php`

#### 2. Jalankan Script PowerShell

```powershell
# Buka PowerShell sebagai Administrator
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
.\deploy-nginx.ps1
```

#### 3. Start Manual (jika script gagal)

```batch
# Start PHP FastCGI
cd C:\php
start php-cgi.exe -b 127.0.0.1:9000

# Start Nginx
cd C:\nginx
start nginx.exe
```

#### 4. Gunakan Startup Scripts

Setelah deployment, gunakan file `start-ecommerce.bat` untuk memulai aplikasi.

---

## ✅ Verifikasi Deployment

### 1. Test Basic Connectivity

```bash
curl -I http://localhost/
# Harus return: HTTP/1.1 200 OK
```

### 2. Test PHP Processing

```bash
curl http://localhost/index.php
# Harus return: HTML content
```

### 3. Test Clean URLs

```bash
curl http://localhost/about
# Harus return: About page content
```

### 4. Test Static Files

```bash
curl -I http://localhost/css/style.css
# Harus return: HTTP/1.1 200 OK dengan Content-Type: text/css
```

### 5. Test Admin Panel

- Akses: http://localhost/admin/
- Login dengan kredensial admin Anda

### 6. Test Upload Functionality

- Login sebagai user
- Coba upload gambar produk (admin panel)
- Pastikan gambar tersimpan di folder `uploaded_img`

---

## 🔧 Troubleshooting

### Error 502 Bad Gateway

```bash
# Check PHP-FPM status
sudo systemctl status php8.2-fpm
sudo systemctl restart php8.2-fpm

# Check nginx error log
sudo tail -f /var/log/nginx/error.log
```

### Clean URLs Tidak Bekerja

```bash
# Pastikan nginx.conf memiliki try_files yang benar
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### File Upload Error

```bash
# Set permissions
sudo chmod 777 /var/www/html/E-Commerce/uploaded_img

# Check nginx.conf untuk client_max_body_size
client_max_body_size 10M;
```

### Database Connection Error

- Update file `config/config.php` dengan kredensial database yang benar
- Pastikan MySQL/MariaDB running
- Test koneksi database

---

## 📋 Post-Deployment Checklist

- [ ] Website dapat diakses via http://localhost
- [ ] Clean URLs berfungsi (contoh: /about, /menu)
- [ ] Static files (CSS, JS, images) loading dengan baik
- [ ] Login/Register berfungsi
- [ ] Admin panel dapat diakses
- [ ] Upload gambar berfungsi
- [ ] Database connection working
- [ ] Shopping cart functionality
- [ ] Checkout process
- [ ] Email notifications (jika ada)

---

## 🔒 Security & Production

### 1. Update Domain

Edit `/etc/nginx/sites-available/ecommerce`:

```nginx
server_name yourdomain.com www.yourdomain.com;
```

### 2. SSL Certificate (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

### 3. Firewall

```bash
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

### 4. Monitoring

```bash
# Setup log rotation
sudo nano /etc/logrotate.d/nginx

# Monitor performance
sudo apt install htop iotop
```

---

## 📊 Performance Comparison

Setelah migrasi, Anda akan mendapatkan:

- ⚡ **20-30% faster** static file serving
- 💾 **Lower memory usage**
- 🔗 **Better concurrent connections**
- 🛡️ **Improved security** (centralized config)
- 📈 **Better scalability**

---

## 📞 Support

Jika mengalami masalah:

1. Check nginx error log: `/var/log/nginx/error.log`
2. Check PHP-FPM log: `/var/log/php8.2-fpm.log`
3. Test konfigurasi: `sudo nginx -t`
4. Restart services: `sudo systemctl restart nginx php8.2-fpm`

**Selamat! Aplikasi E-Commerce Anda sekarang berjalan di Nginx! 🎉**
