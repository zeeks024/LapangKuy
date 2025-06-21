#!/bin/bash

# Script untuk membuat file .env di cPanel
# Jalankan script ini di terminal cPanel atau via SSH

echo "==========================================="
echo "🏟️  LapangKuy - .env Setup untuk cPanel"
echo "==========================================="

# Check if we're in Laravel directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: File artisan tidak ditemukan."
    echo "   Pastikan Anda berada di folder project Laravel."
    exit 1
fi

# Check if .env already exists
if [ -f ".env" ]; then
    echo "⚠️  File .env sudah ada."
    read -p "   Apakah Anda ingin mengganti? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo "   Setup dibatalkan."
        exit 0
    fi
    echo "   Mem-backup file .env yang lama..."
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
fi

echo "📝 Membuat file .env baru..."

# Create .env file from template
cat > .env << 'EOF'
# LapangKuy Production Environment
APP_NAME="LapangKuy"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_TIMEZONE=Asia/Jakarta

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

# Database Configuration - UPDATE WITH YOUR CPANEL DB
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

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
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

# Midtrans Configuration - UPDATE WITH PRODUCTION KEYS
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

VITE_APP_NAME="${APP_NAME}"
EOF

echo "✅ File .env berhasil dibuat!"

# Try to generate APP_KEY
echo "🔑 Generating APP_KEY..."
if command -v php &> /dev/null; then
    if php artisan key:generate --force; then
        echo "✅ APP_KEY berhasil di-generate!"
    else
        echo "⚠️  Error generating APP_KEY. Generate secara manual:"
        echo "   php artisan key:generate"
    fi
else
    echo "⚠️  PHP tidak ditemukan. Generate APP_KEY secara manual:"
    echo "   php artisan key:generate"
fi

echo ""
echo "🎯 LANGKAH SELANJUTNYA:"
echo "1. Edit file .env dan update konfigurasi berikut:"
echo "   - APP_URL (domain Anda)"
echo "   - DB_DATABASE, DB_USERNAME, DB_PASSWORD (info database cPanel)"
echo "   - MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY (production keys)"
echo "   - MAIL_FROM_ADDRESS (email domain Anda)"
echo ""
echo "2. Jalankan perintah optimasi:"
echo "   php artisan config:cache"
echo "   php artisan route:cache"
echo "   php artisan view:cache"
echo ""
echo "3. Set permissions folder:"
echo "   chmod -R 755 storage"
echo "   chmod -R 755 bootstrap/cache"
echo ""
echo "4. Test akses website Anda!"
echo ""
echo "📋 Untuk troubleshooting, akses:"
echo "   https://domain-anda.com/hosting-check.php?check=hosting"
echo ""
echo "==========================================="
echo "🚀 Setup .env selesai!"
echo "==========================================="
