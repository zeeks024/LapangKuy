# Script untuk Membuat File Environment dengan Nama Alternatif
# Karena file .env tidak terlihat di cPanel File Manager

param(
    [string]$Domain = "yourdomain.com",
    [string]$DatabaseName = "laps3233_lapangkuy",
    [string]$DatabaseUser = "laps3233_lapangkuy", 
    [string]$DatabasePassword = "your_password",
    [switch]$CreateVisible
)

Write-Host "=== LapangKuy - Environment File Generator untuk cPanel ===" -ForegroundColor Green
Write-Host ""

# Generate Laravel App Key
function Generate-LaravelKey {
    $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/="
    $key = ""
    for ($i = 0; $i -lt 32; $i++) {
        $key += $characters[(Get-Random -Maximum $characters.Length)]
    }
    return "base64:$key"
}

$appKey = Generate-LaravelKey

# Environment content
$envContent = @"
# === LAPANGKUY PRODUCTION ENVIRONMENT ===
# Generated on: $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")
APP_NAME="LapangKuy"
APP_ENV=production
APP_KEY=$appKey
APP_DEBUG=false
APP_URL=https://$Domain
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
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=$DatabaseName
DB_USERNAME=$DatabaseUser
DB_PASSWORD=$DatabasePassword

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
# Update sesuai email hosting Anda
MAIL_MAILER=smtp
MAIL_HOST=mail.$Domain
MAIL_PORT=587
MAIL_USERNAME=noreply@$Domain
MAIL_PASSWORD=YOUR_EMAIL_PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@$Domain
MAIL_FROM_NAME="LapangKuy"

# === MIDTRANS PAYMENT ===
# SANDBOX - Ganti dengan production key
MIDTRANS_SERVER_KEY=SB-Mid-server-YOUR_SERVER_KEY
MIDTRANS_CLIENT_KEY=SB-Mid-client-YOUR_CLIENT_KEY
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

# === REDIS (Optional - jika hosting support) ===
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# === AWS S3 (Optional) ===
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="`${APP_NAME}"
"@

# Create multiple versions of the file
$files = @()

# 1. Standard .env file
try {
    $envContent | Out-File -FilePath ".env" -Encoding UTF8 -NoNewline
    $files += ".env"
    Write-Host "✓ Created: .env" -ForegroundColor Green
} catch {
    Write-Host "⚠ Failed to create .env: $($_.Exception.Message)" -ForegroundColor Yellow
}

# 2. Visible version for cPanel upload
if ($CreateVisible) {
    $envContent | Out-File -FilePath "env_cpanel.txt" -Encoding UTF8 -NoNewline
    $files += "env_cpanel.txt"
    Write-Host "✓ Created: env_cpanel.txt (rename to .env setelah upload)" -ForegroundColor Green
}

# 3. Alternative names
$alternativeNames = @("environment.txt", "config_env.txt", "laravel_env.txt")
foreach ($name in $alternativeNames) {
    try {
        $envContent | Out-File -FilePath $name -Encoding UTF8 -NoNewline
        $files += $name
        Write-Host "✓ Created: $name" -ForegroundColor Green
    } catch {
        Write-Host "⚠ Failed to create $name" -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "=== File Environment Berhasil Dibuat ===" -ForegroundColor Green
Write-Host "Files created:" -ForegroundColor Yellow
$files | ForEach-Object { Write-Host "  - $_" -ForegroundColor White }

Write-Host ""
Write-Host "=== Langkah Selanjutnya di cPanel ===" -ForegroundColor Cyan
Write-Host "1. Upload salah satu file di atas ke folder project Anda" -ForegroundColor White
Write-Host "2. Rename file menjadi '.env' (dengan titik di depan)" -ForegroundColor White
Write-Host "3. Atau aktifkan 'Show Hidden Files' di File Manager cPanel" -ForegroundColor White
Write-Host ""
Write-Host "=== Cara Rename di cPanel ===" -ForegroundColor Cyan
Write-Host "1. Klik kanan pada file (misal: env_cpanel.txt)" -ForegroundColor White
Write-Host "2. Pilih 'Rename'" -ForegroundColor White
Write-Host "3. Ubah nama menjadi: .env" -ForegroundColor White
Write-Host "4. Klik 'Rename File'" -ForegroundColor White

Write-Host ""
Write-Host "=== Konfigurasi yang Perlu Diupdate ===" -ForegroundColor Cyan
Write-Host "📧 Email: Update MAIL_PASSWORD dengan password email Anda" -ForegroundColor White
Write-Host "💳 Payment: Update MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY" -ForegroundColor White
Write-Host "🔗 Domain: Sudah diset ke https://$Domain" -ForegroundColor White
Write-Host "🗄️ Database: Sudah diset dengan credentials yang diberikan" -ForegroundColor White

# Create instruction file
$instructions = @"
# INSTRUKSI UPLOAD FILE .env KE CPANEL

## Masalah
File .env tidak terlihat di cPanel File Manager karena merupakan hidden file (dimulai dengan titik).

## Solusi 1: Aktifkan Show Hidden Files
1. Login ke cPanel
2. Buka File Manager
3. Klik "Settings" atau ikon gear
4. Centang "Show Hidden Files (dotfiles)"
5. Klik "Save"
6. Refresh halaman

## Solusi 2: Upload dengan Nama Berbeda
1. Upload file: env_cpanel.txt ke folder /home/laps3233/lapangkuy_laravel/
2. Di File Manager, klik kanan file env_cpanel.txt
3. Pilih "Rename"
4. Ubah nama menjadi: .env
5. Klik "Rename File"

## Solusi 3: Via Terminal SSH
```bash
cd /home/laps3233/lapangkuy_laravel
mv env_cpanel.txt .env
```

## Verifikasi
Setelah file .env berhasil dibuat, akses:
https://yourdomain.com/hosting-check.php?check=hosting

## Generated Configuration
- Domain: https://$Domain
- Database: $DatabaseName
- User: $DatabaseUser
- App Key: $appKey

## Catatan Penting
- File .env TIDAK boleh bisa diakses dari browser
- Pastikan permission file .env adalah 644
- Update password database, email, dan Midtrans sesuai kebutuhan
"@

$instructions | Out-File -FilePath "INSTRUKSI_ENV_CPANEL.txt" -Encoding UTF8
Write-Host "📝 Instruksi lengkap tersimpan di: INSTRUKSI_ENV_CPANEL.txt" -ForegroundColor Green
