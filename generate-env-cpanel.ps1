# Script untuk Generate .env Content untuk cPanel
param(
    [string]$Domain = "yourdomain.com",
    [string]$DatabaseName = "laps3233_lapangkuy",
    [string]$DatabaseUser = "laps3233_dbuser",
    [string]$DatabasePassword = "",
    [string]$EmailPassword = "",
    [string]$MidtransServerKey = "",
    [string]$MidtransClientKey = ""
)

Write-Host "=== LapangKuy .env Generator untuk cPanel ===" -ForegroundColor Green
Write-Host ""

# Create .env content
$envContent = @"
# Production Environment for LapangKuy - Generated $(Get-Date)
APP_NAME="LapangKuy"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://$Domain
APP_TIMEZONE=Asia/Jakarta

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=$DatabaseName
DB_USERNAME=$DatabaseUser
DB_PASSWORD=$DatabasePassword

# Session Configuration
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

# Cache Configuration
CACHE_STORE=database
CACHE_PREFIX=lapangkuy_

# Queue Configuration
QUEUE_CONNECTION=database
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local

# Logging
LOG_CHANNEL=stack
LOG_STACK=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mail.$Domain
MAIL_PORT=587
MAIL_USERNAME=noreply@$Domain
MAIL_PASSWORD=$EmailPassword
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@$Domain"
MAIL_FROM_NAME="`${APP_NAME}"

# Midtrans Payment Gateway
MIDTRANS_SERVER_KEY=$MidtransServerKey
MIDTRANS_CLIENT_KEY=$MidtransClientKey
MIDTRANS_IS_PRODUCTION=true
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

# Redis Configuration
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# AWS Configuration (if needed)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="`${APP_NAME}"
"@

# Save to file
$outputFile = ".env.cpanel"
$envContent | Out-File -FilePath $outputFile -Encoding UTF8

Write-Host "✅ File .env.cpanel telah dibuat!" -ForegroundColor Green
Write-Host "📁 Lokasi: $(Resolve-Path $outputFile)" -ForegroundColor Yellow
Write-Host ""
Write-Host "📋 Langkah selanjutnya:" -ForegroundColor Cyan
Write-Host "1. Buka file .env.cpanel dan review isinya" -ForegroundColor White
Write-Host "2. Copy semua isi file .env.cpanel" -ForegroundColor White
Write-Host "3. Login ke cPanel → File Manager" -ForegroundColor White
Write-Host "4. Navigate ke /home/laps3233/lapangkuy_laravel/" -ForegroundColor White
Write-Host "5. Edit file .env yang sudah ada" -ForegroundColor White
Write-Host "6. Hapus semua isi dan paste konten dari .env.cpanel" -ForegroundColor White
Write-Host "7. Save file" -ForegroundColor White
Write-Host "8. Jalankan: php artisan key:generate --force" -ForegroundColor White
Write-Host ""
Write-Host "⚠️  Jangan lupa update nilai-nilai berikut:" -ForegroundColor Yellow
Write-Host "   - DB_PASSWORD: password database yang sebenarnya" -ForegroundColor White
Write-Host "   - MAIL_PASSWORD: password email jika menggunakan email" -ForegroundColor White
Write-Host "   - MIDTRANS_SERVER_KEY & MIDTRANS_CLIENT_KEY: kunci Midtrans production" -ForegroundColor White

# Show current content preview
Write-Host ""
Write-Host "📄 Preview isi file .env.cpanel:" -ForegroundColor Cyan
Write-Host "----------------------------------------" -ForegroundColor DarkGray
Write-Host $envContent -ForegroundColor Gray
Write-Host "----------------------------------------" -ForegroundColor DarkGray

Write-Host ""
Write-Host "💡 Tips Usage:" -ForegroundColor Cyan
Write-Host ".\generate-env-cpanel.ps1 -Domain 'lapangkuy.com' -DatabaseName 'laps3233_lapangkuy' -DatabaseUser 'laps3233_user' -DatabasePassword 'your_password'" -ForegroundColor Gray
