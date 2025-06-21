<?php

/**
 * cPanel Hosting Configuration Helper
 * Place this file in your project root and run it via browser after deployment
 * Access: https://yourdomain.com/hosting-check.php
 */

// Prevent direct access in production
if (!isset($_GET['check']) || $_GET['check'] !== 'hosting') {
    http_response_code(404);
    exit('Page not found');
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LapangKuy - Hosting Configuration Check</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .check { margin: 10px 0; padding: 10px; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        h1 { color: #333; text-align: center; }
        h2 { color: #666; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; }
        .path { font-family: monospace; background: #f8f9fa; padding: 10px; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏟️ LapangKuy - Hosting Configuration Check</h1>
        
        <h2>📋 System Information</h2>
        <div class="info check">
            <strong>PHP Version:</strong> <?php echo PHP_VERSION; ?><br>
            <strong>Server Software:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?><br>
            <strong>Document Root:</strong> <?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown'; ?><br>
            <strong>Current Script Path:</strong> <?php echo __FILE__; ?>
        </div>

        <h2>🔍 Laravel Project Checks</h2>
        
        <?php
        // Check for Laravel project structure
        $laravelRoot = dirname(__FILE__);
        $checks = [
            'artisan' => 'Laravel Artisan Command',
            'composer.json' => 'Composer Configuration',
            'app' => 'App Directory',
            'config' => 'Config Directory',
            'routes' => 'Routes Directory',
            'storage' => 'Storage Directory',
            'vendor' => 'Vendor Directory (Dependencies)',
            '.env' => 'Environment Configuration'
        ];

        foreach ($checks as $file => $description) {
            $path = $laravelRoot . '/' . $file;
            $exists = file_exists($path);
            $class = $exists ? 'success' : 'error';
            $status = $exists ? '✅ Found' : '❌ Missing';
            
            echo "<div class='check $class'>";
            echo "<strong>$description:</strong> $status<br>";
            if ($exists) {
                echo "<small>Path: $path</small>";
            } else {
                echo "<small>Expected at: $path</small>";
            }
            echo "</div>";
        }
        ?>

        <h2>📁 Directory Permissions</h2>
        
        <?php
        $permissionChecks = [
            'storage' => 'Storage Directory',
            'storage/app' => 'Storage App',
            'storage/framework' => 'Storage Framework',
            'storage/logs' => 'Storage Logs',
            'bootstrap/cache' => 'Bootstrap Cache'
        ];

        foreach ($permissionChecks as $dir => $description) {
            $path = $laravelRoot . '/' . $dir;
            if (is_dir($path)) {
                $perms = substr(sprintf('%o', fileperms($path)), -4);
                $writable = is_writable($path);
                $class = $writable ? 'success' : 'warning';
                $status = $writable ? "✅ Writable ($perms)" : "⚠️ Not Writable ($perms)";
                
                echo "<div class='check $class'>";
                echo "<strong>$description:</strong> $status<br>";
                echo "<small>Path: $path</small>";
                echo "</div>";
            } else {
                echo "<div class='check error'>";
                echo "<strong>$description:</strong> ❌ Directory not found<br>";
                echo "<small>Expected at: $path</small>";
                echo "</div>";
            }
        }
        ?>

        <h2>⚙️ Environment Configuration</h2>
        
        <?php
        $envPath = $laravelRoot . '/.env';
        if (file_exists($envPath)) {
            echo "<div class='check success'>✅ .env file found</div>";
            
            $envContent = file_get_contents($envPath);
            $envChecks = [
                'APP_KEY=' => 'Application Key',
                'APP_ENV=' => 'Application Environment',
                'APP_DEBUG=' => 'Debug Mode',
                'APP_URL=' => 'Application URL',
                'DB_DATABASE=' => 'Database Name',
                'DB_USERNAME=' => 'Database Username',
                'DB_PASSWORD=' => 'Database Password'
            ];
            
            foreach ($envChecks as $key => $description) {
                $found = strpos($envContent, $key) !== false;
                $class = $found ? 'success' : 'warning';
                $status = $found ? '✅ Configured' : '⚠️ Missing';
                
                echo "<div class='check $class'>";
                echo "<strong>$description:</strong> $status";
                echo "</div>";
            }
        } else {
            echo "<div class='check error'>❌ .env file not found</div>";
        }
        ?>

        <h2>🔧 PHP Extensions</h2>
        
        <?php
        $requiredExtensions = [
            'pdo' => 'PDO Database',
            'pdo_mysql' => 'MySQL PDO Driver',
            'mbstring' => 'Multibyte String',
            'tokenizer' => 'Tokenizer',
            'xml' => 'XML Parser',
            'ctype' => 'Character Type',
            'json' => 'JSON',
            'bcmath' => 'BC Math',
            'curl' => 'cURL',
            'fileinfo' => 'File Info',
            'openssl' => 'OpenSSL'
        ];

        foreach ($requiredExtensions as $ext => $description) {
            $loaded = extension_loaded($ext);
            $class = $loaded ? 'success' : 'error';
            $status = $loaded ? '✅ Loaded' : '❌ Missing';
            
            echo "<div class='check $class'>";
            echo "<strong>$description ($ext):</strong> $status";
            echo "</div>";
        }
        ?>

        <h2>🚀 Recommended Actions</h2>
        
        <div class="info check">
            <h3>If you see errors above:</h3>
            <ol>
                <li>Ensure all Laravel files are uploaded correctly</li>
                <li>Set proper permissions (755) for storage and bootstrap/cache directories</li>
                <li>Create .env file from .env.production template</li>
                <li>Install missing PHP extensions via cPanel or contact hosting provider</li>
                <li>Run Laravel artisan commands if SSH access is available</li>
            </ol>
            
            <h3>Next Steps:</h3>
            <ol>
                <li>Delete this file after completing checks</li>
                <li>Test your Laravel application functionality</li>
                <li>Setup SSL certificate for HTTPS</li>
                <li>Configure email settings</li>
                <li>Test payment integration (Midtrans)</li>
            </ol>
        </div>

        <div class="warning check">
            <strong>Security Note:</strong> Delete this file (hosting-check.php) after completing your deployment checks to prevent unauthorized access to system information.
        </div>
    </div>
</body>
</html>
