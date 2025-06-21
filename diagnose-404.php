<?php
/**
 * 404 Error Diagnostic Tool untuk lapangkuy.site
 * Upload file ini ke public_html dan akses via browser
 * URL: https://lapangkuy.site/diagnose-404.php?check=404
 * 
 * HAPUS FILE INI SETELAH DIAGNOSIS SELESAI!
 */

// Security check
if(!isset($_GET['check']) || $_GET['check'] !== '404') {
    http_response_code(404);
    die('Page not found');
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Error Diagnosis - LapangKuy</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { color: #28a745; background: #d4edda; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .error { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .warning { color: #856404; background: #fff3cd; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .info { color: #0c5460; background: #d1ecf1; padding: 10px; border-radius: 4px; margin: 10px 0; }
        h1 { color: #333; }
        h2 { color: #666; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 404 Error Diagnosis - LapangKuy</h1>
        
        <div class="info">
            <strong>Diagnosis Time:</strong> <?php echo date('Y-m-d H:i:s'); ?><br>
            <strong>Domain:</strong> <?php echo $_SERVER['HTTP_HOST']; ?><br>
            <strong>Requested URL:</strong> <?php echo $_SERVER['REQUEST_URI']; ?>
        </div>

        <h2>📁 Critical Files Check</h2>
        <?php
        $criticalFiles = [
            'index.php' => 'Laravel Entry Point (WAJIB)',
            '.htaccess' => 'URL Rewriting Rules (WAJIB)',
            '../lapangkuy_laravel/artisan' => 'Laravel Project Root',
            '../lapangkuy_laravel/vendor/autoload.php' => 'Composer Dependencies',
            '../lapangkuy_laravel/.env' => 'Environment Configuration',
            '../lapangkuy_laravel/bootstrap/app.php' => 'Laravel Application Bootstrap',
            '../lapangkuy_laravel/public/index.php' => 'Original Laravel Public Index'
        ];
        
        $missingFiles = [];
        foreach ($criticalFiles as $file => $description) {
            $fullPath = realpath($file) ?: $file;
            $exists = file_exists($file);
            $readable = $exists ? is_readable($file) : false;
            
            if ($exists && $readable) {
                $size = filesize($file);
                $perms = substr(sprintf('%o', fileperms($file)), -4);
                echo "<div class='success'>";
                echo "<strong>✅ $description:</strong> Found<br>";
                echo "<small>Path: $fullPath | Size: {$size}B | Permissions: $perms</small>";
                echo "</div>";
            } elseif ($exists && !$readable) {
                echo "<div class='warning'>";
                echo "<strong>⚠️ $description:</strong> Exists but not readable<br>";
                echo "<small>Path: $fullPath</small>";
                echo "</div>";
                $missingFiles[] = $file;
            } else {
                echo "<div class='error'>";
                echo "<strong>❌ $description:</strong> Missing<br>";
                echo "<small>Expected: $file</small>";
                echo "</div>";
                $missingFiles[] = $file;
            }
        }
        ?>

        <h2>📄 Current index.php Analysis</h2>
        <?php
        if (file_exists('index.php')) {
            $indexContent = file_get_contents('index.php');
            echo "<div class='success'>✅ index.php file exists</div>";
            
            // Check for Laravel-specific content
            $laravelChecks = [
                "require.*vendor/autoload.php" => "Composer Autoloader",
                "require.*bootstrap/app.php" => "Laravel Bootstrap",
                "handleRequest" => "Request Handler",
                "LARAVEL_START" => "Laravel Start Constant"
            ];
            
            echo "<h3>Content Analysis:</h3>";
            foreach ($laravelChecks as $pattern => $description) {
                if (preg_match("/$pattern/i", $indexContent)) {
                    echo "<div class='success'>✅ $description: Found</div>";
                } else {
                    echo "<div class='error'>❌ $description: Missing</div>";
                }
            }
            
            echo "<h3>Current Content:</h3>";
            echo "<div class='info'>";
            echo "<pre>" . htmlspecialchars($indexContent) . "</pre>";
            echo "</div>";
            
        } else {
            echo "<div class='error'>❌ index.php file not found in document root!</div>";
        }
        ?>

        <h2>🔧 .htaccess Analysis</h2>
        <?php
        if (file_exists('.htaccess')) {
            $htaccessContent = file_get_contents('.htaccess');
            echo "<div class='success'>✅ .htaccess file exists</div>";
            
            // Check for important directives
            $htaccessChecks = [
                "RewriteEngine On" => "URL Rewriting Enabled",
                "RewriteRule.*index\.php" => "Front Controller Pattern",
                "mod_rewrite" => "Mod Rewrite Module Check"
            ];
            
            echo "<h3>Content Analysis:</h3>";
            foreach ($htaccessChecks as $pattern => $description) {
                if (preg_match("/$pattern/i", $htaccessContent)) {
                    echo "<div class='success'>✅ $description: Found</div>";
                } else {
                    echo "<div class='warning'>⚠️ $description: Missing or Different</div>";
                }
            }
            
            echo "<h3>Current Content:</h3>";
            echo "<div class='info'>";
            echo "<pre>" . htmlspecialchars($htaccessContent) . "</pre>";
            echo "</div>";
            
        } else {
            echo "<div class='error'>❌ .htaccess file not found!</div>";
        }
        ?>

        <h2>📊 Server Environment</h2>
        <div class="info">
            <table>
                <tr><th>Property</th><th>Value</th></tr>
                <tr><td><strong>Document Root</strong></td><td><?php echo $_SERVER['DOCUMENT_ROOT']; ?></td></tr>
                <tr><td><strong>Script Filename</strong></td><td><?php echo $_SERVER['SCRIPT_FILENAME']; ?></td></tr>
                <tr><td><strong>Current Working Directory</strong></td><td><?php echo getcwd(); ?></td></tr>
                <tr><td><strong>PHP Version</strong></td><td><?php echo PHP_VERSION; ?></td></tr>
                <tr><td><strong>Server Software</strong></td><td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></td></tr>
                <tr><td><strong>HTTP Host</strong></td><td><?php echo $_SERVER['HTTP_HOST']; ?></td></tr>
                <tr><td><strong>Request Method</strong></td><td><?php echo $_SERVER['REQUEST_METHOD']; ?></td></tr>
                <tr><td><strong>Request URI</strong></td><td><?php echo $_SERVER['REQUEST_URI']; ?></td></tr>
            </table>
        </div>

        <h2>📁 Directory Structure</h2>
        <?php
        echo "<h3>Current Directory (public_html):</h3>";
        echo "<div class='info'>";
        $files = scandir('.');
        echo "<ul>";
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            $isDir = is_dir($file) ? '📁' : '📄';
            $size = is_file($file) ? ' (' . filesize($file) . 'B)' : '';
            echo "<li>$isDir $file$size</li>";
        }
        echo "</ul>";
        echo "</div>";
        
        echo "<h3>Laravel Project Directory (../lapangkuy_laravel):</h3>";
        if (is_dir('../lapangkuy_laravel')) {
            echo "<div class='success'>✅ Laravel project directory found</div>";
            echo "<div class='info'>";
            $laravelFiles = scandir('../lapangkuy_laravel');
            echo "<ul>";
            foreach (array_slice($laravelFiles, 2, 10) as $file) { // Skip . and .., show first 10
                $isDir = is_dir("../lapangkuy_laravel/$file") ? '📁' : '📄';
                echo "<li>$isDir $file</li>";
            }
            if (count($laravelFiles) > 12) {
                echo "<li>... and " . (count($laravelFiles) - 12) . " more files</li>";
            }
            echo "</ul>";
            echo "</div>";
        } else {
            echo "<div class='error'>❌ Laravel project directory not found at ../lapangkuy_laravel</div>";
        }
        ?>

        <h2>🔍 Path Testing</h2>
        <?php
        $pathTests = [
            '../lapangkuy_laravel/vendor/autoload.php',
            '../lapangkuy_laravel/bootstrap/app.php',
            '../lapangkuy_laravel/.env'
        ];
        
        foreach ($pathTests as $path) {
            $realPath = realpath($path);
            if ($realPath) {
                echo "<div class='success'>✅ Path resolves: $path → $realPath</div>";
            } else {
                echo "<div class='error'>❌ Path cannot be resolved: $path</div>";
            }
        }
        ?>

        <h2>🚀 Recommended Solutions</h2>
        
        <?php if (count($missingFiles) > 0): ?>
        <div class="error">
            <h3>❌ Critical Issues Found:</h3>
            <p>The following critical files are missing:</p>
            <ul>
                <?php foreach ($missingFiles as $file): ?>
                    <li><?php echo $file; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="warning">
            <h3>🔧 Fix Steps:</h3>
            
            <h4>1. If index.php is missing or incorrect:</h4>
            <pre>
&lt;?php
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../lapangkuy_laravel/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../lapangkuy_laravel/vendor/autoload.php';

(require_once __DIR__.'/../lapangkuy_laravel/bootstrap/app.php')
    ->handleRequest(Request::capture());
            </pre>
            
            <h4>2. If .htaccess is missing:</h4>
            <pre>
&lt;IfModule mod_rewrite.c&gt;
    &lt;IfModule mod_negotiation.c&gt;
        Options -MultiViews -Indexes
    &lt;/IfModule&gt;

    RewriteEngine On

    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
&lt;/IfModule&gt;
            </pre>
            
            <h4>3. File Structure Should Be:</h4>
            <pre>
/home/laps3233/
├── lapangkuy_laravel/          # Laravel project
│   ├── app/, config/, vendor/
│   ├── .env
│   └── ...
└── public_html/                # Web accessible
    ├── index.php              # Modified Laravel entry
    ├── .htaccess               # URL rewrite rules  
    ├── css/, js/, assets/      # From Laravel public/
    └── ...
            </pre>
        </div>

        <div class="info">
            <h3>📞 Next Steps:</h3>
            <ol>
                <li><strong>If files are missing:</strong> Re-upload Laravel project and copy public folder contents</li>
                <li><strong>If files exist but wrong content:</strong> Update index.php and .htaccess with correct content</li>
                <li><strong>If still not working:</strong> Contact hosting support about mod_rewrite and file permissions</li>
                <li><strong>After fixing:</strong> Delete this diagnostic file for security</li>
            </ol>
        </div>

        <div class="error">
            <strong>🔒 Security Warning:</strong> Delete this file (diagnose-404.php) after completing diagnosis to prevent unauthorized access to server information.
        </div>
    </div>
</body>
</html>
