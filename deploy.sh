#!/bin/bash

# LapangKuy Laravel Deployment Script for cPanel
# Jalankan script ini setelah upload ke cPanel via SSH

echo "=== LapangKuy Laravel Deployment Script ==="
echo "Starting deployment process..."

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "Error: artisan file not found. Make sure you're in the Laravel project directory."
    exit 1
fi

# Install composer dependencies
echo "Installing Composer dependencies..."
if command -v composer &> /dev/null; then
    composer install --optimize-autoloader --no-dev
else
    echo "Warning: Composer not found. Please install dependencies manually."
fi

# Generate application key if not exists
if ! grep -q "APP_KEY=base64:" .env; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Clear and cache configurations
echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "Caching configurations for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "Setting proper permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Run database migrations
echo "Running database migrations..."
read -p "Do you want to run database migrations? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate --force
fi

# Create symbolic link for storage (if needed)
echo "Creating storage symbolic link..."
php artisan storage:link

echo "=== Deployment completed successfully! ==="
echo "Please check your website to ensure everything is working properly."
echo ""
echo "Next steps:"
echo "1. Update your .env file with production database credentials"
echo "2. Set APP_URL to your domain"
echo "3. Configure email settings"
echo "4. Set up SSL certificate"
echo "5. Test all functionalities"
