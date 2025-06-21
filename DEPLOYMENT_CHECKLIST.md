# Laravel Project Upload Checklist untuk cPanel

## Sebelum Upload

### 1. Persiapan Project
- [ ] Update file `.env.production` dengan konfigurasi hosting
- [ ] Test aplikasi di local environment
- [ ] Backup database local
- [ ] Compress project (exclude node_modules, vendor)

### 2. File yang Harus Diupload
- [ ] Semua file project kecuali:
  - `node_modules/` (akan diinstall ulang)
  - `vendor/` (akan diinstall ulang atau upload manual)
  - `.git/` (tidak perlu)
  - `storage/logs/` (buat folder baru)

### 3. Database Preparation
- [ ] Export database dari phpMyAdmin local
- [ ] Siapkan credentials database untuk hosting

## Saat di cPanel

### 4. File Management
- [ ] Upload zip file ke root directory (bukan public_html)
- [ ] Extract file di root directory
- [ ] Copy semua isi folder `public/` ke `public_html/`
- [ ] Edit `public_html/index.php` untuk path yang benar

### 5. Database Setup
- [ ] Buat database baru di MySQL Databases
- [ ] Buat user database dengan password kuat
- [ ] Assign user ke database (ALL PRIVILEGES)
- [ ] Import database melalui phpMyAdmin

### 6. Environment Configuration
- [ ] Rename `.env.production` menjadi `.env`
- [ ] Update database credentials di `.env`
- [ ] Update APP_URL dengan domain yang benar
- [ ] Set APP_DEBUG=false
- [ ] Set APP_ENV=production

### 7. Dependencies & Permissions
- [ ] Upload folder `vendor/` atau install via SSH/terminal
- [ ] Set permission 755 untuk folder `storage/`
- [ ] Set permission 755 untuk folder `bootstrap/cache/`

### 8. Laravel Artisan Commands
Jika ada akses SSH, jalankan:
- [ ] `php artisan key:generate`
- [ ] `php artisan migrate --force`
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] `php artisan storage:link`

### 9. Testing
- [ ] Akses website dan cek homepage
- [ ] Test login/register
- [ ] Test booking functionality
- [ ] Test payment (Midtrans)
- [ ] Cek error logs jika ada masalah

### 10. Post-Deployment
- [ ] Install SSL certificate
- [ ] Update APP_URL ke HTTPS
- [ ] Test email functionality
- [ ] Setup cron jobs jika diperlukan
- [ ] Backup files penting

## Troubleshooting Common Issues

### Error 500
- Cek error logs di cPanel
- Pastikan permission folder storage dan bootstrap/cache
- Cek file .env format

### Database Connection Error
- Periksa credentials database di .env
- Pastikan database host (biasanya localhost)
- Cek user database sudah di-assign ke database

### File/Image Upload Issues
- Cek permission folder storage
- Pastikan symbolic link storage dibuat
- Cek konfigurasi upload di php.ini

### Route Not Found
- Pastikan file .htaccess ada di public_html
- Cek mod_rewrite enabled di hosting
- Clear route cache

## File Backup Penting
Sebelum deployment, backup:
- [ ] `.env` file
- [ ] Database (SQL dump)
- [ ] Storage files (uploads)
- [ ] Custom configurations
