<?php
/**
 * Quick Error 500 Diagnostic for LapangKwy
 * Upload this to public_html and access: https://lapangkuy.site/quick-diagnostic.php
 * REMOVE AFTER FIXING ERROR!
 */

// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

?><!DOCTYPE html>
<html>
<head>
    <title>Quick Diagnostic - LapangKuy Error 500</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; background: #e8f5e8; padding: 10px; margin: 5px 0; }
        .error { color: red; background: #ffe8e8; padding: 10px; margin: 5px 0; }
        .warning { color: orange; background: #fff8e8; padding: 10px; margin: 5px 0; }
        pre { background: #f5f5f5; padding: 10px; overflow: auto; }
    </style>
</head>
<body>
    <h1>🔍 Quick Error 500 Diagnostic</h1>
    
    <h2>PHP Status</h2>
    <div class="success">PHP Version: <?php echo PHP_VERSION; ?></div>
    <div class="success">Memory Limit: <?php echo ini_get('memory_limit'); ?></div>
    
    <h2>Critical Files Check</h2>
    <?php
    $files = [
        'index.php' => 'Laravel Entry Point',
        '.htaccess' => 'URL Rewrite Rules',
        '../lapangkuy_laravel/.env' => 'Environment File',
        '../lapangkuy_laravel/vendor/autoload.php' => 'Composer Autoload',
        '../lapangkuy_laravel/bootstrap/app.php' => 'Laravel Bootstrap'
    ];
    
    foreach ($files as $file => $desc) {
        if (file_exists($file)) {
            $readable = is_readable($file);
            $size = filesize($file);
            $class = $readable ? 'success' : 'warning';
            echo "<div class='$class'>✅ $desc: Found ($size bytes) - " . ($readable ? "Readable" : "Not Readable") . "</div>";
        } else {
            echo "<div class='error'>❌ $desc: Missing - $file</div>";
        }
    }
    ?>
    
    <h2>Laravel Test</h2>
    <?php
    try {
        if (file_exists('../lapangkuy_laravel/vendor/autoload.php')) {
            echo "<div class='success'>✅ Composer autoload found</div>";
            
            // Try to load Laravel
            require '../lapangkuy_laravel/vendor/autoload.php';
            echo "<div class='success'>✅ Composer autoload loaded successfully</div>";
            
            if (file_exists('../lapangkuy_laravel/bootstrap/app.php')) {
                echo "<div class='success'>✅ Laravel bootstrap file found</div>";
                
                // Try to create Laravel app (this might show the actual error)
                $app = require_once '../lapangkuy_laravel/bootstrap/app.php';
                echo "<div class='success'>✅ Laravel application loaded successfully</div>";
                
            } else {
                echo "<div class='error'>❌ Laravel bootstrap file missing</div>";
            }
        } else {
            echo "<div class='error'>❌ Composer autoload missing</div>";
        }
    } catch (Exception $e) {
        echo "<div class='error'>❌ Laravel Error: " . $e->getMessage() . "</div>";
        echo "<pre>Stack Trace:\n" . $e->getTraceAsString() . "</pre>";
    } catch (Error $e) {
        echo "<div class='error'>❌ PHP Error: " . $e->getMessage() . "</div>";
        echo "<pre>Stack Trace:\n" . $e->getTraceAsString() . "</pre>";
    }
    ?>
    
    <h2>Environment File Check</h2>
    <?php
    if (file_exists('../lapangkuy_laravel/.env')) {
        echo "<div class='success'>✅ .env file exists</div>";
        
        $envContent = file_get_contents('../lapangkuy_laravel/.env');
        $lines = explode("\n", $envContent);
        
        $checkKeys = ['APP_KEY', 'APP_DEBUG', 'APP_ENV', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE'];
        
        foreach ($checkKeys as $key) {
            $found = false;
            foreach ($lines as $line) {
                if (strpos($line, $key . '=') === 0) {
                    $value = substr($line, strlen($key) + 1);
                    if ($key === 'APP_KEY') {
                        $display = empty($value) ? 'MISSING' : 'SET (***hidden***)';
                    } else {
                        $display = $value;
                    }
                    echo "<div class='success'>✅ $key = $display</div>";
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                echo "<div class='error'>❌ $key not found in .env</div>";
            }
        }
    } else {
        echo "<div class='error'>❌ .env file missing</div>";
    }
    ?>
    
    <h2>Directory Permissions</h2>
    <?php
    $dirs = [
        '../lapangkuy_laravel/storage',
        '../lapangkuy_laravel/bootstrap/cache'
    ];
    
    foreach ($dirs as $dir) {
        if (is_dir($dir)) {
            $writable = is_writable($dir);
            $perms = substr(sprintf('%o', fileperms($dir)), -4);
            $class = $writable ? 'success' : 'error';
            echo "<div class='$class'>" . ($writable ? '✅' : '❌') . " $dir: Permissions $perms - " . ($writable ? 'Writable' : 'Not Writable') . "</div>";
        } else {
            echo "<div class='error'>❌ Directory missing: $dir</div>";
        }
    }
    ?>
    
    <h2>Error Logs</h2>
    <?php
    $logFiles = [
        '../lapangkuy_laravel/storage/logs/laravel.log',
        '../error_log',
        'error_log'
    ];
    
    foreach ($logFiles as $logFile) {
        if (file_exists($logFile)) {
            echo "<h3>$logFile</h3>";
            $content = file_get_contents($logFile);
            $lines = explode("\n", $content);
            $recentLines = array_slice($lines, -20); // Last 20 lines
            echo "<pre>" . htmlspecialchars(implode("\n", $recentLines)) . "</pre>";
        }
    }
    ?>
    
    <p><small>Diagnostic completed at <?php echo date('Y-m-d H:i:s'); ?></small></p>
</body>
</html>
