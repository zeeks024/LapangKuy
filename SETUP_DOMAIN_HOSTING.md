# 🚀 Panduan Setup Domain dan Hosting Baru untuk LapangKuy Laravel

## 1. 🌐 Setup Domain dan DNS

### A. Konfigurasi DNS (di Domain Registrar)
1. **Login ke Control Panel Domain** (GoDaddy, Namecheap, dll)
2. **Update Nameservers** dengan nameserver hosting Anda:
   ```
   ns1.namahosting.com
   ns2.namahosting.com
   ```
   *Atau sesuai yang diberikan hosting provider*

3. **Tunggu Propagasi DNS** (24-48 jam maksimal)

### B. Verifikasi Domain di Hosting
1. Login ke cPanel hosting
2. Pastikan domain sudah terdaftar di **Addon Domains** atau **Main Domain**

## 2. 🔧 Setup Awal cPanel

### A. Akses cPanel
1. Login ke: `https://cpanel.namahosting.com:2083`
2. Atau: `https://namadomain.com/cpanel`
3. Username/Password sesuai email aktivasi hosting

### B. Pengaturan Dasar
1. **File Manager** - Set untuk show hidden files:
   - Buka File Manager
   - Settings → Show Hidden Files ✅
   
2. **PHP Version** - Pastikan PHP 8.2+:
   - MultiPHP Manager → Pilih domain → PHP 8.2

3. **Database** - Buat database MySQL:
   - MySQL Databases
   - Create Database: `username_lapangkuy`
   - Create User: `username_lapang`
   - Add User to Database (ALL PRIVILEGES)

## 3. 📁 Upload dan Setup Project Laravel

### A. Persiapan File Upload
```powershell
# Di komputer local, jalankan:
cd c:\xampp\htdocs\lapangkuy_laravel
.\prepare-deployment.ps1 -CreateZip -CleanCache
```

### B. Upload Project
1. **Upload ke Root Directory** (bukan public_html):
   ```
   /home/username/ ← Upload zip file di sini
   ```
2. **Extract** file zip
3. **Copy Public Files**:
   - Copy semua isi folder `public/` ke `public_html/`

### C. Edit Index File
Edit `public_html/index.php`:
```php
<?php
// Ganti path ini:
require __DIR__.'/../lapangkuy_laravel/vendor/autoload.php';
$app = require_once __DIR__.'/../lapangkuy_laravel/bootstrap/app.php';
```

## 4. ⚙️ Konfigurasi Environment (.env)

### A. Buat File .env (Solusi Hidden Files)
Karena file `.env` tidak terlihat di cPanel:

1. **Buat file biasa** dengan nama `env_production.txt`
2. **Isi dengan konfigurasi**:
```env
APP_NAME="LapangKuy"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://namadomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=username_lapangkuy
DB_USERNAME=username_lapang
DB_PASSWORD=password_database_anda

SESSION_DRIVER=database
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=mail.namadomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@namadomain.com
MAIL_PASSWORD=password_email_anda
MAIL_FROM_ADDRESS=noreply@namadomain.com

MIDTRANS_SERVER_KEY=your_production_server_key
MIDTRANS_CLIENT_KEY=your_production_client_key
MIDTRANS_IS_PRODUCTION=true
```

3. **Rename via File Manager**: `env_production.txt` → `.env`

### B. Generate App Key
Via Terminal cPanel atau SSH:
```bash
cd lapangkuy_laravel
php artisan key:generate
```

## 5. 🗄️ Setup Database

### A. Export Database dari Local
1. Buka phpMyAdmin local (http://localhost/phpmyadmin)
2. Export database `lapangkuy`
3. Download file SQL

### B. Import ke Hosting
1. Buka phpMyAdmin di cPanel
2. Select database yang dibuat
3. Import file SQL dari local

### C. Update Database Config
Pastikan `.env` sesuai dengan database hosting:
```env
DB_DATABASE=username_lapangkuy
DB_USERNAME=username_lapang
DB_PASSWORD=password_yang_dibuat
```

## 6. 🔒 Setup SSL Certificate

### A. Install SSL (Let's Encrypt - Gratis)
1. Di cPanel → SSL/TLS
2. Let's Encrypt SSL
3. Issue certificate untuk domain
4. Enable "Force HTTPS Redirect"

### B. Update Environment
```env
APP_URL=https://namadomain.com
```

## 7. 📧 Setup Email

### A. Buat Email Account
1. cPanel → Email Accounts
2. Create: `noreply@namadomain.com`
3. Set password yang kuat

### B. Test Email Configuration
```bash
php artisan tinker
Mail::raw('Test email', function($message) {
    $message->to('your-email@gmail.com')->subject('Test');
});
```

## 8. 💳 Setup Midtrans Production

### A. Midtrans Account
1. Login ke https://dashboard.midtrans.com
2. Switch ke **Production Environment**
3. Copy Server Key dan Client Key

### B. Update Environment
```env
MIDTRANS_SERVER_KEY=SB-Mid-server-xxx (Production)
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxx (Production)
MIDTRANS_IS_PRODUCTION=true
```

## 9. 🛠️ Optimasi Production

### A. Install Dependencies
```bash
cd lapangkuy_laravel
composer install --optimize-autoloader --no-dev
```

### B. Cache Configurations
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### C. Set Permissions
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### D. Create Storage Link
```bash
php artisan storage:link
```

## 10. ✅ Testing dan Verifikasi

### A. Test Basic Functionality
- [ ] Homepage loading
- [ ] Login/Register
- [ ] Booking system
- [ ] Payment integration

### B. Gunakan Diagnostic Tool
Upload dan akses: `https://namadomain.com/hosting-check.php?check=hosting`

### C. Check Error Logs
cPanel → Error Logs untuk debugging

## 11. 🔧 Troubleshooting Umum

### Error 500
```bash
# Check permissions
chmod -R 755 storage bootstrap/cache

# Clear caches
php artisan config:clear
php artisan cache:clear
```

### Database Connection Error
- Periksa credentials di `.env`
- Pastikan user database di-assign ke database

### Email Not Working
- Test SMTP settings
- Check firewall hosting untuk port 587

### Payment Issues
- Verify Midtrans production keys
- Check callback URL settings

## 12. 📋 Checklist Final

- [ ] Domain pointing ke hosting ✅
- [ ] SSL certificate installed ✅
- [ ] Database imported dan connected ✅
- [ ] File `.env` configured ✅
- [ ] Email sending working ✅
- [ ] Midtrans payment testing ✅
- [ ] File permissions set ✅
- [ ] Laravel optimized for production ✅

## 📞 Support Information

- **Hosting Support**: Hubungi support hosting untuk masalah server
- **Domain Support**: Hubungi registrar domain untuk masalah DNS
- **Laravel Issues**: Cek error logs dan documentation

---
**Catatan**: Simpan semua credential dengan aman dan jangan share informasi sensitif.
