    # Panduan Hosting Laravel ke cPanel

## 1. Persiapan File

### A. Compress File Project
1. Buat file zip dari seluruh project kecuali folder `node_modules` dan `vendor`
2. File yang harus di-upload: semua kecuali `node_modules`, `vendor`, dan `.env`

### B. Upload ke cPanel
1. Login ke cPanel hosting Anda
2. Buka File Manager
3. Upload file zip ke folder utama (bukan public_html)
4. Extract file zip tersebut

## 2. Konfigurasi Folder

### A. Pindahkan File Public
1. Pindahkan semua isi folder `public` ke folder `public_html`
2. Folder struktur akan menjadi:
   ```
   /home/username/
   ├── lapangkuy_laravel/ (folder project)
   └── public_html/ (isi dari folder public)
   ```

### B. Update Path di index.php
Edit file `public_html/index.php` dan ubah path menjadi:
```php
require __DIR__.'/../lapangkuy_laravel/vendor/autoload.php';
$app = require_once __DIR__.'/../lapangkuy_laravel/bootstrap/app.php';
```

## 3. Konfigurasi Database

### A. Buat Database di cPanel
1. Buka MySQL Database di cPanel
2. Buat database baru (misal: `username_lapangkuy`)
3. Buat user database dengan password yang kuat
4. Assign user ke database dengan ALL PRIVILEGES

### B. Import Database
1. Export database dari localhost (phpmyadmin)
2. Import ke database hosting melalui phpMyAdmin di cPanel

## 4. Konfigurasi Environment

### A. Membuat File .env (PENTING!)
⚠️ **File .env tidak di-upload untuk keamanan**. Anda harus membuat manual di cPanel:

**Metode 1: Via File Manager cPanel**
1. Login ke cPanel → File Manager
2. Navigasi ke folder project Laravel: `/home/username/lapangkuy_laravel/`
3. Klik tombol **"+ File"** 
4. Nama file: `.env` (dengan titik di depan)
5. Copy template dari file `.env.cpanel` yang sudah disediakan

**Metode 2: Copy dari Template**
1. Gunakan file `.env.cpanel` sebagai template
2. Copy isi file tersebut ke file `.env` baru di cPanel
3. Update semua nilai yang bertanda `GANTI_INI`

**Template .env untuk cPanel:**
```env
APP_NAME="LapangKuy"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://domain-anda.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=username_lapangkuy
DB_USERNAME=username_dbuser
DB_PASSWORD=your_db_password

# Midtrans Production
MIDTRANS_SERVER_KEY=your_production_server_key
MIDTRANS_CLIENT_KEY=your_production_client_key
MIDTRANS_IS_PRODUCTION=false
```

### B. Generate Application Key
Setelah membuat .env, wajib generate APP_KEY:
```bash
php artisan key:generate
```
Atau generate manual di: https://generate-random.org/laravel-key-generator

## 5. Install Dependencies

### A. Via SSH (jika tersedia)
```bash
cd /home/username/lapangkuy_laravel
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### B. Via File Manager (jika tidak ada SSH)
1. Upload folder `vendor` yang sudah di-generate dari localhost
2. Atau gunakan terminal di cPanel jika tersedia

## 6. Set Permissions

Set permission folder berikut ke 755 atau 775:
- `storage/`
- `bootstrap/cache/`

## 7. Generate App Key

Jika belum ada APP_KEY, jalankan:
```bash
php artisan key:generate
```

## 8. Testing

1. Akses domain Anda
2. Pastikan aplikasi berjalan dengan baik
3. Test semua fitur utama

## 9. Optimasi Production

### A. Cache Configuration
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### B. Optimize Autoloader
```bash
composer install --optimize-autoloader --no-dev
```

## 10. SSL Certificate

1. Install SSL certificate melalui cPanel
2. Update APP_URL ke https://

## Troubleshooting

### Error 500
- Cek error logs di cPanel
- Pastikan permission folder storage dan bootstrap/cache
- Cek file .env sudah benar

### Database Connection Error
- Pastikan kredensial database benar
- Cek host database (biasanya localhost)

### File Not Found
- Pastikan path di index.php sudah benar
- Cek struktur folder sesuai panduan

## File Penting untuk Backup

Sebelum upload, backup file berikut:
- `.env`
- `database/` (jika ada custom seeder)
- `storage/app/` (jika ada file upload)

## Keamanan

1. Pastikan `.env` tidak bisa diakses dari web
2. Set APP_DEBUG=false di production
3. Gunakan HTTPS
4. Update dependency secara berkala
