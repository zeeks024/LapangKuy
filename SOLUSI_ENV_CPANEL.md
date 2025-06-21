# 🚨 Solusi: File .env Tidak Ada di cPanel

## Masalah
File `.env` tidak ditemukan di cPanel setelah upload project Laravel.

## Penyebab
- File `.env` sengaja tidak di-upload untuk keamanan
- File `.env` berisi informasi sensitif seperti database password
- Setiap environment (local, staging, production) memiliki konfigurasi berbeda

## ✅ Solusi Langkah demi Langkah

### Metode 1: Membuat File .env Melalui File Manager cPanel

1. **Login ke cPanel** → Buka **File Manager**

2. **Navigasi ke folder project Laravel** (bukan public_html)
   ```
   /home/username/lapangkuy_laravel/
   ```

3. **Klik tombol "+ File"** untuk membuat file baru

4. **Nama file:** `.env` (dengan titik di depan)

5. **Copy isi dari template di bawah** dan paste ke file `.env`:

```env
# Production Environment untuk LapangKuy
APP_NAME="LapangKuy"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://DOMAIN_ANDA.com

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file
PHP_CLI_SERVER_WORKERS=4
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Database Configuration - UPDATE DENGAN DATA ANDA
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=username_namadb
DB_USERNAME=username_dbuser
DB_PASSWORD=password_database_anda

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=587
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@DOMAIN_ANDA.com"
MAIL_FROM_NAME="${APP_NAME}"

# Midtrans Configuration - UPDATE DENGAN KEY PRODUCTION
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

6. **Update konfigurasi berikut:**
   - `APP_URL` → ganti dengan domain Anda
   - `DB_DATABASE` → nama database yang dibuat di cPanel
   - `DB_USERNAME` → username database
   - `DB_PASSWORD` → password database
   - `MAIL_FROM_ADDRESS` → ganti dengan email domain Anda
   - `MIDTRANS_SERVER_KEY` & `MIDTRANS_CLIENT_KEY` → key production dari Midtrans

### Metode 2: Upload File .env Terpisah

1. **Buat file `.env` di local** dengan isi template di atas (sudah disesuaikan untuk production)

2. **Upload file `.env`** secara terpisah ke folder project Laravel di cPanel

3. **Pastikan lokasi:** `/home/username/lapangkuy_laravel/.env`

### Metode 3: Copy dari .env.production (Jika Ada)

1. **Di File Manager cPanel**, cari file `.env.production`

2. **Copy file** → Rename menjadi `.env`

3. **Edit file `.env`** dan sesuaikan konfigurasi

## 🔑 Generate APP_KEY

Setelah membuat file `.env`, Anda perlu generate APP_KEY:

### Via SSH (Jika tersedia):
```bash
cd /home/username/lapangkuy_laravel
php artisan key:generate
```

### Via Terminal cPanel (Jika tersedia):
```bash
php artisan key:generate
```

### Manual (Jika tidak ada akses terminal):
1. Buka website: https://generate-random.org/laravel-key-generator
2. Generate key baru
3. Copy key yang dihasilkan
4. Edit file `.env` dan ganti:
   ```env
   APP_KEY=base64:KEY_YANG_DIHASILKAN
   ```

## 🗄️ Konfigurasi Database

Pastikan konfigurasi database di `.env` sesuai dengan yang dibuat di cPanel:

1. **Di cPanel** → **MySQL Databases**
2. **Catat informasi:**
   - Database name: `username_lapangkuy`
   - Database user: `username_dbuser`
   - Password: (yang Anda buat)

3. **Update di .env:**
   ```env
   DB_DATABASE=username_lapangkuy
   DB_USERNAME=username_dbuser
   DB_PASSWORD=password_anda
   ```

## 📧 Konfigurasi Email (Opsional)

Untuk notifikasi email booking:

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.domainanda.com
MAIL_PORT=587
MAIL_USERNAME=noreply@domainanda.com
MAIL_PASSWORD=password_email
MAIL_FROM_ADDRESS=noreply@domainanda.com
```

## 🔍 Verifikasi

1. **Cek file `.env` ada** di `/home/username/lapangkuy_laravel/.env`

2. **Test akses website** - jika masih error, cek error logs

3. **Gunakan hosting-check.php** untuk diagnosis:
   ```
   https://domain-anda.com/hosting-check.php?check=hosting
   ```

## ⚠️ Tips Penting

- **Jangan pernah commit file `.env` ke Git**
- **Backup file `.env` setelah dikonfigurasi**
- **Gunakan password yang kuat untuk database**
- **Set `APP_DEBUG=false` di production**
- **Gunakan HTTPS dan update `APP_URL` accordingly**

## 🆘 Jika Masih Error

1. **Cek permission file .env** → harus 644
2. **Cek error logs** di cPanel Error Logs
3. **Pastikan syntax .env** tidak ada spasi berlebih
4. **Restart aplikasi** jika ada opsi di hosting

File `.env` adalah jantung konfigurasi Laravel, pastikan semua nilai sudah benar sesuai environment hosting Anda!
