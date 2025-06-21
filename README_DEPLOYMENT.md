# 🏟️ LapangKuy Laravel - Deployment ke cPanel

Proyek Laravel untuk sistem booking lapangan olahraga dengan integrasi pembayaran Midtrans.

## 📋 Persiapan Deployment

### Sistem Requirements
- PHP 8.2 atau lebih tinggi
- MySQL/MariaDB
- Composer
- Mod_Rewrite enabled
- SSL Certificate (recommended)

### File yang Telah Disiapkan untuk Deployment

1. **`HOSTING_GUIDE.md`** - Panduan lengkap step-by-step deployment
2. **`DEPLOYMENT_CHECKLIST.md`** - Checklist untuk memastikan semua langkah selesai
3. **`prepare-deployment.ps1`** - Script PowerShell untuk persiapan deployment
4. **`deploy.sh`** - Script bash untuk deployment di server (jika ada SSH)
5. **`hosting-check.php`** - Tool untuk mengecek konfigurasi hosting
6. **`.env.production`** - Template environment untuk production
7. **`index.cpanel.php`** - Alternative index.php untuk hosting tertentu

## 🚀 Quick Start Deployment

### 1. Persiapan di Local (Windows)

```powershell
# Jalankan script persiapan
.\prepare-deployment.ps1 -CleanCache -CreateZip

# Atau manual
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 2. Upload ke cPanel

1. **Upload Project**
   - Upload file zip ke root directory (bukan public_html)
   - Extract files

2. **Setup Public Files**
   - Copy semua isi folder `public/` ke `public_html/`
   - Edit `public_html/index.php`:
   ```php
   require __DIR__.'/../lapangkuy_laravel/vendor/autoload.php';
   $app = require_once __DIR__.'/../lapangkuy_laravel/bootstrap/app.php';
   ```

3. **Database Setup**
   - Buat database MySQL di cPanel
   - Import database dari backup local
   - Update credentials di `.env`

4. **Environment Configuration**
   ```bash
   # Copy dan rename
   cp .env.production .env
   
   # Edit sesuai hosting
   APP_URL=https://yourdomain.com
   DB_DATABASE=username_lapangkuy
   DB_USERNAME=username_dbuser
   DB_PASSWORD=your_password
   ```

### 3. Finalisasi (via SSH atau Terminal cPanel)

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Generate key dan cache
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Database
php artisan migrate --force
php artisan storage:link
```

## 🔧 Troubleshooting

### Error 500
- Cek error logs di cPanel
- Pastikan permission folder storage (755)
- Cek format file .env

### Database Connection Error
- Verifikasi credentials database
- Pastikan host database (biasanya `localhost`)

### File Upload Issues
- Cek permission storage folder
- Pastikan symbolic link dibuat: `php artisan storage:link`

## 📁 Struktur File Setelah Deployment

```
/home/username/
├── lapangkuy_laravel/          # Project Laravel
│   ├── app/
│   ├── config/
│   ├── storage/
│   ├── .env
│   └── ...
└── public_html/                # Public files
    ├── index.php
    ├── assets/
    ├── css/
    ├── js/
    └── storage -> ../lapangkuy_laravel/storage/app/public
```

## 🔒 Keamanan Production

1. **Environment**
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - Strong `APP_KEY`

2. **Database**
   - Strong database password
   - Limited database user privileges

3. **Files**
   - Remove development files
   - Proper file permissions
   - Hide sensitive files via .htaccess

## 📧 Konfigurasi Email

Update di `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=no-reply@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_FROM_ADDRESS=no-reply@yourdomain.com
```

## 💳 Konfigurasi Midtrans

Update di `.env`:
```env
MIDTRANS_SERVER_KEY=your_production_server_key
MIDTRANS_CLIENT_KEY=your_production_client_key
MIDTRANS_IS_PRODUCTION=true
```

## 📞 Support

Jika mengalami masalah deployment:

1. Cek file `hosting-check.php` untuk diagnosa
2. Review error logs di cPanel
3. Konsultasi dengan penyedia hosting
4. Ikuti troubleshooting guide di `HOSTING_GUIDE.md`

## 🗂️ File Backup Penting

Backup sebelum deployment:
- Database (SQL dump)
- File `.env` yang sudah dikonfigurasi
- Storage files (uploads)
- Custom configurations

---

**Catatan:** Hapus file `hosting-check.php` setelah deployment selesai untuk keamanan.
