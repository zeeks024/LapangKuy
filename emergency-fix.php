<?php
/**
 * Emergency Fix for Error 500 - LapangKuy
 * Upload this to public_html and access: https://lapangkuy.site/emergency-fix.php
 * REMOVE AFTER USING!
 */

// Security check - change this password
$EMERGENCY_PASSWORD = 'lapangkuy2024fix';

if (!isset($_GET['password']) || $_GET['password'] !== $EMERGENCY_PASSWORD) {
    die('Access denied');
}

?><!DOCTYPE html>
<html>
<head>
    <title>Emergency Fix - LapangKuy Error 500</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; background: #e8f5e8; padding: 10px; margin: 5px 0; }
        .error { color: red; background: #ffe8e8; padding: 10px; margin: 5px 0; }
        .warning { color: orange; background: #fff8e8; padding: 10px; margin: 5px 0; }
        button { background: #007cba; color: white; padding: 10px 20px; border: none; cursor: pointer; margin: 5px; }
        button:hover { background: #005a87; }
    </style>
</head>
<body>
    <h1>🚨 Emergency Fix Tool</h1>
    
    <?php
    $action = $_GET['action'] ?? '';
    
    switch ($action) {
        case 'create_env':
            echo "<h2>Creating Minimal .env File</h2>";
            $envContent = <<<ENV
APP_NAME=LapangKuy
APP_ENV=production
APP_KEY=base64:J8S7SFGSFgsdfgSDGsdgSDGsdgSDGsdgSDGsQ=
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://lapangkuy.site

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=lapangku_main
DB_USERNAME=lapangku_admin
DB_PASSWORD=YourCpanelPassword

BROADCAST_CONNECTION=log
CACHE_STORE=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120

MIDTRANS_SERVER_KEY=your_midtrans_server_key
MIDTRANS_CLIENT_KEY=your_midtrans_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
ENV;
            
            if (file_put_contents('../lapangkuy_laravel/.env', $envContent)) {
                echo "<div class='success'>✅ .env file created successfully</div>";
            } else {
                echo "<div class='error'>❌ Failed to create .env file</div>";
            }
            break;
            
        case 'generate_key':
            echo "<h2>Generating New APP_KEY</h2>";
            $key = 'base64:' . base64_encode(random_bytes(32));
            echo "<div class='success'>✅ New APP_KEY generated: $key</div>";
            echo "<div class='warning'>⚠️ You need to update this in your .env file manually</div>";
            
            // Try to update .env if it exists
            if (file_exists('../lapangkuy_laravel/.env')) {
                $envContent = file_get_contents('../lapangkuy_laravel/.env');
                $envContent = preg_replace('/APP_KEY=.*/', 'APP_KEY=' . $key, $envContent);
                if (file_put_contents('../lapangkuy_laravel/.env', $envContent)) {
                    echo "<div class='success'>✅ APP_KEY updated in .env file</div>";
                }
            }
            break;
            
        case 'fix_permissions':
            echo "<h2>Fixing Directory Permissions</h2>";
            $dirs = [
                '../lapangkuy_laravel/storage',
                '../lapangkuy_laravel/storage/logs',
                '../lapangkuy_laravel/storage/app',
                '../lapangkuy_laravel/storage/framework',
                '../lapangkuy_laravel/storage/framework/cache',
                '../lapangkuy_laravel/storage/framework/sessions',
                '../lapangkuy_laravel/storage/framework/views',
                '../lapangkuy_laravel/bootstrap/cache'
            ];
            
            foreach ($dirs as $dir) {
                if (is_dir($dir)) {
                    if (chmod($dir, 0755)) {
                        echo "<div class='success'>✅ Fixed permissions for $dir</div>";
                    } else {
                        echo "<div class='error'>❌ Failed to fix permissions for $dir</div>";
                    }
                } else {
                    echo "<div class='warning'>⚠️ Directory doesn't exist: $dir</div>";
                }
            }
            break;
            
        case 'clear_cache':
            echo "<h2>Clearing Laravel Cache</h2>";
            $cacheFiles = [
                '../lapangkuy_laravel/bootstrap/cache/config.php',
                '../lapangkuy_laravel/bootstrap/cache/routes.php',
                '../lapangkuy_laravel/bootstrap/cache/services.php'
            ];
            
            foreach ($cacheFiles as $file) {
                if (file_exists($file)) {
                    if (unlink($file)) {
                        echo "<div class='success'>✅ Deleted cache file: $file</div>";
                    } else {
                        echo "<div class='error'>❌ Failed to delete: $file</div>";
                    }
                } else {
                    echo "<div class='warning'>⚠️ Cache file doesn't exist: $file</div>";
                }
            }
            break;
            
        case 'create_htaccess':
            echo "<h2>Creating .htaccess File</h2>";
            $htaccessContent = <<<HTACCESS
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
HTACCESS;
            
            if (file_put_contents('.htaccess', $htaccessContent)) {
                echo "<div class='success'>✅ .htaccess file created successfully</div>";
            } else {
                echo "<div class='error'>❌ Failed to create .htaccess file</div>";
            }
            break;
            
        default:
            echo "<h2>Available Emergency Fixes</h2>";
            echo "<p>Choose an action to fix common Error 500 causes:</p>";
            echo "<button onclick=\"window.location.href='?password=$EMERGENCY_PASSWORD&action=create_env'\">Create Minimal .env File</button><br>";
            echo "<button onclick=\"window.location.href='?password=$EMERGENCY_PASSWORD&action=generate_key'\">Generate New APP_KEY</button><br>";
            echo "<button onclick=\"window.location.href='?password=$EMERGENCY_PASSWORD&action=fix_permissions'\">Fix Directory Permissions</button><br>";
            echo "<button onclick=\"window.location.href='?password=$EMERGENCY_PASSWORD&action=clear_cache'\">Clear Laravel Cache</button><br>";
            echo "<button onclick=\"window.location.href='?password=$EMERGENCY_PASSWORD&action=create_htaccess'\">Create .htaccess File</button><br>";
            break;
    }
    ?>
    
    <br><br>
    <a href="?password=<?php echo $EMERGENCY_PASSWORD; ?>">🏠 Back to Menu</a> | 
    <a href="quick-diagnostic.php">🔍 Run Diagnostic</a>
    
    <p><small>Emergency fix tool - <?php echo date('Y-m-d H:i:s'); ?></small></p>
</body>
</html>
