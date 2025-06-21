<?php
/**
 * Basic PHP Test - Upload ke public_html untuk test dasar
 * URL: https://lapangkuy.site/test-basic.php
 */

echo "<!DOCTYPE html>";
echo "<html><head><title>Basic PHP Test - LapangKuy</title>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .info{color:blue;}</style>";
echo "</head><body>";

echo "<h1>🧪 Basic PHP Test - LapangKuy</h1>";

echo "<div class='success'>";
echo "<h2>✅ PHP is Working!</h2>";
echo "<p><strong>Timestamp:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>Server:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
echo "</div>";

echo "<div class='info'>";
echo "<h3>📊 Server Information</h3>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p><strong>Script Path:</strong> " . __FILE__ . "</p>";
echo "<p><strong>Current Directory:</strong> " . getcwd() . "</p>";
echo "<p><strong>Host:</strong> " . $_SERVER['HTTP_HOST'] . "</p>";
echo "<p><strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "</div>";

echo "<div class='info'>";
echo "<h3>📁 Directory Contents</h3>";
$files = scandir('.');
echo "<ul>";
foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    $type = is_dir($file) ? '📁 Directory' : '📄 File';
    $size = is_file($file) ? ' (' . filesize($file) . ' bytes)' : '';
    echo "<li>$type: $file$size</li>";
}
echo "</ul>";
echo "</div>";

echo "<div class='info'>";
echo "<h3>🔍 Laravel Project Check</h3>";
$laravelPath = '../lapangkuy_laravel';
if (is_dir($laravelPath)) {
    echo "<p class='success'>✅ Laravel project directory found: $laravelPath</p>";
    
    $laravelFiles = ['artisan', 'composer.json', '.env', 'vendor/autoload.php', 'bootstrap/app.php'];
    foreach ($laravelFiles as $file) {
        $fullPath = $laravelPath . '/' . $file;
        if (file_exists($fullPath)) {
            echo "<p class='success'>✅ Found: $file</p>";
        } else {
            echo "<p class='error'>❌ Missing: $file</p>";
        }
    }
} else {
    echo "<p class='error'>❌ Laravel project directory not found: $laravelPath</p>";
}
echo "</div>";

echo "<div class='info'>";
echo "<h3>🚀 Next Steps</h3>";
echo "<ol>";
echo "<li>If this page loads successfully, PHP is working</li>";
echo "<li>Check if Laravel files are properly uploaded</li>";
echo "<li>Create/fix index.php with correct Laravel entry point</li>";
echo "<li>Create/fix .htaccess for URL rewriting</li>";
echo "<li>Test Laravel application</li>";
echo "<li><strong>Delete this test file after completing setup</strong></li>";
echo "</ol>";
echo "</div>";

echo "<div style='margin-top:20px; padding:10px; background:#f0f0f0; border-radius:5px;'>";
echo "<small><strong>Note:</strong> This is a test file. Delete it after confirming PHP works for security reasons.</small>";
echo "</div>";

echo "</body></html>";
?>
