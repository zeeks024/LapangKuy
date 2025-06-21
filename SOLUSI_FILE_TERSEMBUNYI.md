# Masalah File Tersembunyi (.env) di cPanel

## 🔍 Mengapa File .env Tidak Terlihat?

File dengan awalan titik (.) seperti `.env`, `.htaccess`, `.gitignore` adalah **hidden files** di sistem Unix/Linux. cPanel File Manager secara default menyembunyikan file-file ini.

## ✅ Solusi untuk Melihat File Tersembunyi di cPanel

### Metode 1: Aktifkan Show Hidden Files di File Manager

1. **Login ke cPanel**
2. **Buka File Manager**
3. **Klik "Settings" atau ikon gear** di pojok kanan atas
4. **Centang "Show Hidden Files (dotfiles)"**
5. **Klik "Save"**
6. **Refresh halaman** - sekarang file `.env` akan terlihat

### Metode 2: Buat File .env Manual di File Manager

Jika masih tidak terlihat, buat manual:

1. **Di File Manager, klik "File"**
2. **Pilih "New File"**
3. **Nama file: `.env`** (dengan titik di depan)
4. **Klik "Create New File"**
5. **Edit file tersebut dengan konfigurasi yang benar**

### Metode 3: Via Terminal/SSH (Jika Tersedia)

```bash
# Masuk ke directory project
cd /home/laps3233/lapangkuy_laravel

# Lihat semua file termasuk hidden
ls -la

# Buat file .env dari template
cp .env.production .env

# Edit file .env
nano .env
```

## 📝 Isi File .env untuk cPanel

Berikut template `.env` yang siap pakai untuk hosting cPanel Anda:

```env
# === LAPANGKUY PRODUCTION ENVIRONMENT ===
APP_NAME="LapangKuy"
APP_ENV=production
APP_KEY=base64:GENERATE_NEW_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_TIMEZONE=Asia/Jakarta

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file
PHP_CLI_SERVER_WORKERS=4
BCRYPT_ROUNDS=12

# === LOGGING ===
LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# === DATABASE CONFIGURATION ===
# Update sesuai database cPanel Anda
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=laps3233_lapangkuy
DB_USERNAME=laps3233_lapangkuy
DB_PASSWORD=YOUR_DATABASE_PASSWORD

# === SESSION & CACHE ===
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
CACHE_STORE=database
CACHE_PREFIX=lapangkuy_

# === MAIL CONFIGURATION ===
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=YOUR_EMAIL_PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="LapangKuy"

# === MIDTRANS PAYMENT ===
MIDTRANS_SERVER_KEY=SB-Mid-server-YOUR_SERVER_KEY
MIDTRANS_CLIENT_KEY=SB-Mid-client-YOUR_CLIENT_KEY
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

# === REDIS (Jika tersedia) ===
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# === AWS S3 (Jika menggunakan) ===
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

## 🔧 Langkah-Langkah Setelah Membuat File .env

### 1. Generate Application Key

**Via Terminal SSH:**
```bash
cd /home/laps3233/lapangkuy_laravel
php artisan key:generate
```

**Via File Manager (Manual):**
- Buka situs: https://generate-random.org/laravel-key-generator
- Copy key yang dihasilkan
- Replace `GENERATE_NEW_KEY_HERE` dengan key tersebut

### 2. Update Database Credentials

Sesuaikan dengan database cPanel Anda:
```env
DB_DATABASE=laps3233_lapangkuy    # Nama database Anda
DB_USERNAME=laps3233_lapangkuy    # Username database
DB_PASSWORD=your_actual_password   # Password database
```

### 3. Update Domain

```env
APP_URL=https://yourdomain.com    # Domain hosting Anda
```

### 4. Test Konfigurasi

Akses: `https://yourdomain.com/hosting-check.php?check=hosting`

## ⚠️ Troubleshooting

### File .env Masih Tidak Terlihat?

1. **Clear browser cache**
2. **Logout dan login kembali ke cPanel**
3. **Coba browser lain**
4. **Hubungi support hosting** untuk memastikan hidden files bisa ditampilkan

### Permission Denied?

```bash
# Set permission yang benar
chmod 644 .env
```

### File .env Corrupt?

```bash
# Backup file lama
mv .env .env.backup

# Buat file baru
touch .env

# Copy isi dari template
nano .env
```

## 🔒 Keamanan File .env

Pastikan file `.env` tidak bisa diakses dari web dengan menambahkan ke `.htaccess`:

```apache
<FilesMatch "^\.env">
    Order allow,deny
    Deny from all
</FilesMatch>
```

## 📞 Bantuan Lebih Lanjut

Jika masih mengalami masalah:
1. Screenshot error yang muncul
2. Cek error logs di cPanel
3. Hubungi support hosting untuk bantuan teknis
4. Pastikan PHP version minimal 8.2
