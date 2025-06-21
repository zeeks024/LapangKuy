# 🚨 Error 500 Internal Server Error - Analysis & Solutions

## ❌ **Error yang Terjadi:**
```
Internal Server Error
The server encountered an internal error or misconfiguration and was unable to complete your request.
Additionally, a 500 Internal Server Error error was encountered while trying to use an ErrorDocument to handle the request.
```

## 🔍 **Kemungkinan Penyebab Error 500:**

### **1. File .env Bermasalah atau Missing** (Paling Umum)
- Database credentials salah
- APP_KEY tidak ada atau invalid
- Environment variables corrupt

### **2. Database Connection Error**
- Database credentials salah di .env
- Database server tidak accessible
- Database tidak exist

### **3. Permissions Bermasalah**
- Folder storage tidak writable (755)
- Folder bootstrap/cache tidak writable (755)
- File permissions salah

### **4. PHP Configuration Error**
- PHP version tidak compatible
- Required extensions missing
- Memory limit too low

### **5. Laravel Configuration Cache**
- Config cache corrupt
- Route cache corrupt
- View cache corrupt

### **6. .htaccess Bermasalah**
- Syntax error di .htaccess
- Mod_rewrite directives conflict

## 🛠️ **Solusi Step-by-Step:**

### **STEP 1: Create Error Diagnostic Tool**
Mari saya buat tool untuk diagnose exact error:

```php
<?php
// error-diagnostic.php - Upload ke public_html untuk diagnose error 500
// URL: https://lapangkuy.site/error-diagnostic.php?diagnose=500

if (!isset($_GET['diagnose']) || $_GET['diagnose'] !== '500') {
    die('Access denied');
}

// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

?><!DOCTYPE html>
<html>
<head>
    <title>Error 500 Diagnostic - LapangKuy</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; background: #d4edda; padding: 10px; margin: 10px 0; }
        .error { color: red; background: #f8d7da; padding: 10px; margin: 10px 0; }
        .warning { color: orange; background: #fff3cd; padding: 10px; margin: 10px 0; }
        .info { color: blue; background: #d1ecf1; padding: 10px; margin: 10px 0; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔍 Error 500 Diagnostic Tool</h1>
    
    <h2>📊 PHP Environment Check</h2>
    <div class='info'>
        <strong>PHP Version:</strong> <?php echo PHP_VERSION; ?><br>
        <strong>Server:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?><br>
        <strong>Error Reporting:</strong> <?php echo error_reporting(); ?><br>
        <strong>Display Errors:</strong> <?php echo ini_get('display_errors') ? 'On' : 'Off'; ?><br>
        <strong>Memory Limit:</strong> <?php echo ini_get('memory_limit'); ?><br>
        <strong>Max Execution Time:</strong> <?php echo ini_get('max_execution_time'); ?>s
    </div>

    <h2>📁 Critical Files Check</h2>
    <?php
    $criticalFiles = [
        'index.php' => 'Laravel Entry Point',
        '.htaccess' => 'URL Rewrite Rules',
        '../lapangkuy_laravel/.env' => 'Environment Configuration',
        '../lapangkuy_laravel/vendor/autoload.php' => 'Composer Autoloader',
        '../lapangkuy_laravel/bootstrap/app.php' => 'Laravel Bootstrap'
    ];
    
    foreach ($criticalFiles as $file => $description) {
        if (file_exists($file)) {
            $size = filesize($file);
            $perms = substr(sprintf('%o', fileperms($file)), -4);
            echo "<div class='success'>✅ $description: Found (Size: {$size}B, Permissions: $perms)</div>";
        } else {
            echo "<div class='error'>❌ $description: Missing ($file)</div>";
        }
    }
    ?>

    <h2>🗂️ Directory Permissions Check</h2>
    <?php
    $directories = [
        '../lapangkuy_laravel/storage' => 'Storage Directory',
        '../lapangkuy_laravel/storage/app' => 'Storage App',
        '../lapangkuy_laravel/storage/framework' => 'Storage Framework',
        '../lapangkuy_laravel/storage/logs' => 'Storage Logs',
        '../lapangkuy_laravel/bootstrap/cache' => 'Bootstrap Cache'
    ];
    
    foreach ($directories as $dir => $description) {
        if (is_dir($dir)) {
            $perms = substr(sprintf('%o', fileperms($dir)), -4);
            $writable = is_writable($dir);
            $class = $writable ? 'success' : 'error';
            $status = $writable ? "✅ Writable ($perms)" : "❌ Not Writable ($perms)";
            echo "<div class='$class'>$description: $status</div>";
        } else {
            echo "<div class='error'>❌ $description: Directory not found ($dir)</div>";
        }
    }
    ?>

    <h2>⚙️ Environment File Analysis</h2>
    <?php
    $envPath = '../lapangkuy_laravel/.env';
    if (file_exists($envPath)) {
        echo "<div class='success'>✅ .env file found</div>";
        
        $envContent = file_get_contents($envPath);
        $envLines = explode("\n", $envContent);
        
        // Check critical env variables
        $criticalVars = [
            'APP_KEY' => 'Application Key',
            'APP_ENV' => 'Application Environment',
            'DB_DATABASE' => 'Database Name',
            'DB_USERNAME' => 'Database Username',
            'DB_PASSWORD' => 'Database Password'
        ];
        
        foreach ($criticalVars as $var => $description) {
            $found = false;
            $value = '';
            foreach ($envLines as $line) {
                if (strpos($line, $var . '=') === 0) {
                    $found = true;
                    $parts = explode('=', $line, 2);
                    $value = isset($parts[1]) ? trim($parts[1]) : '';
                    break;
                }
            }
            
            if ($found) {
                if ($var === 'DB_PASSWORD') {
                    $displayValue = !empty($value) ? '[SET]' : '[EMPTY]';
                } elseif ($var === 'APP_KEY') {
                    $displayValue = !empty($value) ? '[SET]' : '[MISSING]';
                } else {
                    $displayValue = $value;
                }
                
                $class = !empty($value) ? 'success' : 'warning';
                echo "<div class='$class'>$description ($var): $displayValue</div>";
            } else {
                echo "<div class='error'>❌ $description ($var): Not found</div>";
            }
        }
        
    } else {
        echo "<div class='error'>❌ .env file not found at: $envPath</div>";
    }
    ?>

    <h2>🧪 Basic Laravel Test</h2>
    <?php
    echo "<h3>Testing Laravel Bootstrap...</h3>";
    
    try {
        // Test autoloader
        if (file_exists('../lapangkuy_laravel/vendor/autoload.php')) {
            echo "<div class='info'>Testing Composer autoloader...</div>";
            require_once '../lapangkuy_laravel/vendor/autoload.php';
            echo "<div class='success'>✅ Composer autoloader loaded successfully</div>";
        } else {
            echo "<div class='error'>❌ Composer autoloader not found</div>";
        }
        
        // Test Laravel bootstrap
        if (file_exists('../lapangkuy_laravel/bootstrap/app.php')) {
            echo "<div class='info'>Testing Laravel bootstrap...</div>";
            
            // Capture any errors during bootstrap
            ob_start();
            $errorOutput = '';
            
            try {
                $app = require_once '../lapangkuy_laravel/bootstrap/app.php';
                echo "<div class='success'>✅ Laravel application bootstrapped successfully</div>";
                
                // Test basic Laravel functionality
                if (isset($app)) {
                    echo "<div class='success'>✅ Laravel app instance created</div>";
                    
                    // Test environment
                    if (method_exists($app, 'environment')) {
                        $env = $app->environment();
                        echo "<div class='info'>Environment: $env</div>";
                    }
                }
                
            } catch (Exception $e) {
                echo "<div class='error'>❌ Laravel bootstrap failed: " . $e->getMessage() . "</div>";
                echo "<div class='error'>File: " . $e->getFile() . " Line: " . $e->getLine() . "</div>";
            } catch (Error $e) {
                echo "<div class='error'>❌ PHP Error during bootstrap: " . $e->getMessage() . "</div>";
                echo "<div class='error'>File: " . $e->getFile() . " Line: " . $e->getLine() . "</div>";
            }
            
            $errorOutput = ob_get_clean();
            if (!empty($errorOutput)) {
                echo "<div class='warning'>Bootstrap output: <pre>$errorOutput</pre></div>";
            }
            
        } else {
            echo "<div class='error'>❌ Laravel bootstrap file not found</div>";
        }
        
    } catch (Exception $e) {
        echo "<div class='error'>❌ Fatal error during testing: " . $e->getMessage() . "</div>";
    }
    ?>

    <h2>📋 Error Log Check</h2>
    <?php
    $logPaths = [
        '../lapangkuy_laravel/storage/logs/laravel.log',
        '/var/log/apache2/error.log',
        '/var/log/httpd/error_log',
        '../logs/error_log'
    ];
    
    foreach ($logPaths as $logPath) {
        if (file_exists($logPath) && is_readable($logPath)) {
            echo "<div class='success'>✅ Found log file: $logPath</div>";
            
            $logContent = file_get_contents($logPath);
            $logLines = explode("\n", $logContent);
            $recentLines = array_slice($logLines, -10); // Last 10 lines
            
            echo "<div class='info'>";
            echo "<strong>Recent log entries:</strong>";
            echo "<pre>" . htmlspecialchars(implode("\n", $recentLines)) . "</pre>";
            echo "</div>";
            break;
        }
    }
    ?>

    <h2>🚀 Recommended Fixes</h2>
    <div class='warning'>
        <h3>Based on common Error 500 causes:</h3>
        <ol>
            <li><strong>Fix .env file:</strong> Ensure all required variables are set</li>
            <li><strong>Fix permissions:</strong> Set storage/ and bootstrap/cache/ to 755</li>
            <li><strong>Clear cache:</strong> Delete cached files if accessible</li>
            <li><strong>Check database:</strong> Verify database connection</li>
            <li><strong>Generate APP_KEY:</strong> If missing or invalid</li>
        </ol>
    </div>

    <div class='error'>
        <strong>Security:</strong> Delete this diagnostic file after fixing the error!
    </div>
</body>
</html>
```

### **STEP 2: Quick .env Fix**
Create a minimal working .env:

```env
APP_NAME="LapangKuy"
APP_ENV=production
APP_KEY=base64:your_key_here
APP_DEBUG=false
APP_URL=https://lapangkuy.site

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### **STEP 3: Fix Permissions**
Via cPanel File Manager or SSH:
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### **STEP 4: Clear Laravel Cache**
If you have SSH access:
```bash
cd /path/to/lapangkuy_laravel
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## 📞 **Emergency Fix Checklist:**

1. **✅ Upload error-diagnostic.php** to public_html
2. **✅ Access diagnostic tool** to identify exact error
3. **✅ Fix .env file** with correct database credentials
4. **✅ Set proper permissions** (755 for storage folders)
5. **✅ Clear Laravel cache** if possible
6. **✅ Test website** after each fix
7. **✅ Clean up** diagnostic files

## 🔧 **Common Quick Fixes:**

### **If Database Error:**
Update .env with correct credentials from cPanel

### **If Permission Error:**
Set folder permissions to 755 via File Manager

### **If APP_KEY Missing:**
Generate new key (if SSH available):
```bash
php artisan key:generate
```

### **If Cache Issues:**
Delete cache files manually:
- Delete contents of `bootstrap/cache/`
- Delete contents of `storage/framework/cache/`

Error 500 biasanya mudah diperbaiki setelah kita tahu penyebab exactnya dari diagnostic tool!
