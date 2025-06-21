# ✅ Checklist Setup Domain & Hosting Baru untuk LapangKuy

## 🌐 Setup Domain (lapangkuy.site)

### Di Domain Registrar (Tempat Beli Domain)
- [ ] **Login ke akun domain registrar**
- [ ] **Cari domain lapangkuy.site di dashboard**
- [ ] **Cek status domain:** Pastikan status "Active" atau "OK"
- [ ] **Set Nameservers:** 
  ```
  Ganti dari default ke nameserver hosting Anda
  Contoh:
  ns1.hostingprovider.com
  ns2.hostingprovider.com
  ```
- [ ] **Disable Domain Privacy (opsional):** Untuk troubleshooting awal
- [ ] **Save perubahan dan tunggu propagasi**

### Informasi yang Dibutuhkan:
- [ ] **Nama hosting provider Anda**
- [ ] **Nameserver dari hosting (biasanya ns1 dan ns2)**
- [ ] **Kapan domain dibeli**
- [ ] **Kapan nameserver diubah**

## 🏠 Setup Hosting (cPanel)

### Login ke cPanel Hosting
- [ ] **Buka cPanel URL** (biasanya yourdomain.com:2083 atau yourdomain.com/cpanel)
- [ ] **Login dengan credentials hosting**

### Tambah Domain di cPanel
- [ ] **Cari menu 'Addon Domains' atau 'Parked Domains'**
- [ ] **Add New Domain:**
  - Domain Name: `lapangkuy.site`
  - Document Root: `public_html/lapangkuy` (atau `public_html` jika domain utama)
  - Create FTP Account: Opsional
- [ ] **Save/Add Domain**

### Setup Database
- [ ] **MySQL Databases** → Create New Database
  - Database Name: `laps3233_lapangkuy` (sesuai prefix hosting)
- [ ] **Create Database User:**
  - Username: `laps3233_lapangkuy`
  - Password: [Password kuat]
- [ ] **Add User to Database** dengan ALL PRIVILEGES

## 📁 Upload & Setup Laravel

### Upload Files
- [ ] **Compress project Laravel** (exclude node_modules, vendor)
- [ ] **Upload ke root directory** (bukan public_html)
- [ ] **Extract files**
- [ ] **Copy semua isi folder 'public' ke document root domain**

### Konfigurasi Laravel
- [ ] **Edit index.php di document root:**
  ```php
  require __DIR__.'/../lapangkuy_laravel/vendor/autoload.php';
  $app = require_once __DIR__.'/../lapangkuy_laravel/bootstrap/app.php';
  ```

- [ ] **Create file .env:**
  ```env
  APP_URL=https://lapangkuy.site
  DB_DATABASE=laps3233_lapangkuy
  DB_USERNAME=laps3233_lapangkuy
  DB_PASSWORD=[your_db_password]
  ```

### Upload Dependencies
- [ ] **Upload folder 'vendor'** atau install via SSH/Terminal
- [ ] **Set Permissions:**
  - storage/: 755
  - bootstrap/cache/: 755

### Database Import
- [ ] **Export database dari localhost** (phpMyAdmin)
- [ ] **Import ke hosting database** via phpMyAdmin cPanel

## 🔧 Finalisasi Setup

### Laravel Configuration
Jika ada SSH/Terminal access:
- [ ] `php artisan key:generate`
- [ ] `php artisan migrate --force`
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan storage:link`

### SSL Certificate
- [ ] **Install SSL** via cPanel (Let's Encrypt gratis)
- [ ] **Force HTTPS redirect**
- [ ] **Update APP_URL ke HTTPS**

## 🧪 Testing

### Basic Tests
- [ ] **HTTP Access:** `http://lapangkuy.site`
- [ ] **HTTPS Access:** `https://lapangkuy.site`
- [ ] **Homepage loads properly**
- [ ] **Database connection works**

### Application Tests
- [ ] **User registration/login**
- [ ] **Field listing**
- [ ] **Booking system**
- [ ] **Payment (Midtrans) - use sandbox first**
- [ ] **Email notifications**

## 🚨 Troubleshooting Common Issues

### Domain Tidak Bisa Diakses
```powershell
# Test DNS resolution
nslookup lapangkuy.site
ping lapangkuy.site
```

**Jika gagal:**
1. Cek nameserver di domain registrar
2. Tunggu DNS propagation (2-48 jam)
3. Cek domain sudah ditambah di cPanel

### Error 500
1. Cek error logs di cPanel
2. Pastikan permissions folder storage
3. Cek file .env format

### Database Connection Error
1. Verifikasi credentials database
2. Cek database user privileges
3. Test connection via phpMyAdmin

## 📞 Support Contacts

### Domain Issues:
- **Domain Registrar Support** (untuk nameserver, DNS)

### Hosting Issues:
- **Hosting Provider Support** (untuk cPanel, server)

### Laravel Issues:
- Cek file `TROUBLESHOOT_DOMAIN.md`
- Cek error logs di `storage/logs/`

## ⏰ Timeline Estimasi

- **Nameserver Update:** 15 menit
- **DNS Propagation:** 2-48 jam ⏳
- **File Upload:** 30 menit
- **Laravel Setup:** 1 jam
- **SSL Installation:** 15 menit
- **Testing:** 30 menit

**Total:** 2-3 jam (+ waktu DNS propagation)

---

## 📋 Quick Command Reference

### PowerShell (Windows)
```powershell
# Test domain
nslookup lapangkuy.site
ping lapangkuy.site
Test-NetConnection lapangkuy.site -Port 80
Test-NetConnection lapangkuy.site -Port 443

# Check DNS propagation online
Start-Process "https://dnschecker.org"
```

### Bash (Linux/SSH)
```bash
# Laravel commands
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan storage:link

# Permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

---

**💡 Tip:** Simpan file ini dan centang setiap item saat Anda menyelesaikannya!
