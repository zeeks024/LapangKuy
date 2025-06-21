<?php
/**
 * URL Rewrite & mod_rewrite Test Tool
 * Upload ke public_html untuk test masalah routing Laravel
 * URL: https://lapangkuy.site/test-rewrite.php?test=rewrite
 * 
 * HAPUS FILE INI SETELAH TESTING!
 */

if (!isset($_GET['test']) || $_GET['test'] !== 'rewrite') {
    http_response_code(404);
    die('Page not found');
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Rewrite Test - LapangKuy</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { color: #28a745; background: #d4edda; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .error { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .warning { color: #856404; background: #fff3cd; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .info { color: #0c5460; background: #d1ecf1; padding: 10px; border-radius: 4px; margin: 10px 0; }
        h1 { color: #333; }
        h2 { color: #666; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; max-height: 300px; }
        .url-list { list-style: none; padding: 0; }
        .url-list li { margin: 5px 0; padding: 10px; background: #f8f9fa; border-radius: 4px; }
        .url-list a { text-decoration: none; color: #007bff; font-weight: bold; }
        .url-list a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 URL Rewrite & Routing Test - LapangKuy</h1>
        
        <div class="info">
            <strong>Test Time:</strong> <?php echo date('Y-m-d H:i:s'); ?><br>
            <strong>Domain:</strong> <?php echo $_SERVER['HTTP_HOST']; ?><br>
            <strong>Request URI:</strong> <?php echo $_SERVER['REQUEST_URI']; ?>
        </div>

        <h2>📄 .htaccess File Analysis</h2>
        <?php
        $htaccessPath = '.htaccess';
        if (file_exists($htaccessPath)) {
            echo "<div class='success'>✅ .htaccess file found</div>";
            
            $htaccessContent = file_get_contents($htaccessPath);
            $fileSize = filesize($htaccessPath);
            $filePerms = substr(sprintf('%o', fileperms($htaccessPath)), -4);
            
            echo "<div class='info'>";
            echo "<strong>File Size:</strong> {$fileSize} bytes<br>";
            echo "<strong>Permissions:</strong> $filePerms<br>";
            echo "<strong>Last Modified:</strong> " . date('Y-m-d H:i:s', filemtime($htaccessPath));
            echo "</div>";
            
            // Check for critical mod_rewrite directives
            $checks = [
                'RewriteEngine\s+On' => 'URL Rewriting Enabled',
                'RewriteRule.*index\.php.*\[L\]' => 'Front Controller Pattern',
                'RewriteCond.*REQUEST_FILENAME.*!-f' => 'File Not Found Condition',
                'RewriteCond.*REQUEST_FILENAME.*!-d' => 'Directory Not Found Condition',
                'mod_rewrite' => 'mod_rewrite Module Reference',
                'Options.*-Indexes' => 'Directory Browsing Disabled'
            ];
            
            echo "<h3>Critical Directives Check:</h3>";
            $foundDirectives = 0;
            foreach ($checks as $pattern => $description) {
                if (preg_match("/$pattern/i", $htaccessContent)) {
                    echo "<div class='success'>✅ $description: Found</div>";
                    $foundDirectives++;
                } else {
                    echo "<div class='warning'>⚠️ $description: Missing or Different</div>";
                }
            }
            
            if ($foundDirectives >= 4) {
                echo "<div class='success'>";
                echo "<strong>✅ .htaccess appears to be correctly configured for Laravel routing</strong>";
                echo "</div>";
            } else {
                echo "<div class='error'>";
                echo "<strong>❌ .htaccess may be incomplete or incorrect for Laravel routing</strong>";
                echo "</div>";
            }
            
            echo "<h3>Current .htaccess Content:</h3>";
            echo "<div class='info'><pre>" . htmlspecialchars($htaccessContent) . "</pre></div>";
            
        } else {
            echo "<div class='error'>";
            echo "<strong>❌ .htaccess file not found!</strong><br>";
            echo "This is the most likely cause of Laravel routing 404 errors.";
            echo "</div>";
        }
        ?>

        <h2>🔍 Server Configuration</h2>
        <div class="info">
            <table style="width:100%; border-collapse: collapse;">
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 8px; font-weight: bold;">Server Software</td>
                    <td style="padding: 8px;"><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></td>
                </tr>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 8px; font-weight: bold;">Document Root</td>
                    <td style="padding: 8px;"><?php echo $_SERVER['DOCUMENT_ROOT']; ?></td>
                </tr>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 8px; font-weight: bold;">Script Path</td>
                    <td style="padding: 8px;"><?php echo __FILE__; ?></td>
                </tr>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 8px; font-weight: bold;">PHP Version</td>
                    <td style="padding: 8px;"><?php echo PHP_VERSION; ?></td>
                </tr>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 8px; font-weight: bold;">mod_rewrite Status</td>
                    <td style="padding: 8px;">
                        <?php
                        if (function_exists('apache_get_modules')) {
                            $modules = apache_get_modules();
                            if (in_array('mod_rewrite', $modules)) {
                                echo "<span style='color: green; font-weight: bold;'>✅ Available</span>";
                            } else {
                                echo "<span style='color: red; font-weight: bold;'>❌ Not Available</span>";
                            }
                        } else {
                            echo "<span style='color: orange; font-weight: bold;'>⚠️ Cannot detect (typical on shared hosting)</span>";
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </div>

        <h2>🧪 Laravel Routes Test</h2>
        <div class="info">
            <p><strong>Test these URLs after fixing .htaccess:</strong></p>
            <ul class="url-list">
                <li>
                    🏠 <a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/" target="_blank">Homepage</a>
                    <small> - Should work (main page)</small>
                </li>
                <li>
                    🔐 <a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/login" target="_blank">Login Page</a>
                    <small> - Test Laravel routing</small>
                </li>
                <li>
                    📝 <a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/register" target="_blank">Register Page</a>
                    <small> - Test Laravel routing</small>
                </li>
                <li>
                    🏟️ <a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/lapangan" target="_blank">Lapangan List</a>
                    <small> - Test custom routes</small>
                </li>
                <li>
                    📊 <a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/dashboard" target="_blank">Dashboard</a>
                    <small> - Test protected routes</small>
                </li>
            </ul>
        </div>

        <h2>🔧 Fix Instructions</h2>
        
        <?php if (!file_exists('.htaccess')): ?>
        <div class="error">
            <h3>❌ Missing .htaccess File</h3>
            <p><strong>Solution:</strong> Create .htaccess file in public_html/ with this content:</p>
            <pre>&lt;IfModule mod_rewrite.c&gt;
    &lt;IfModule mod_negotiation.c&gt;
        Options -MultiViews -Indexes
    &lt;/IfModule&gt;

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Handle X-XSRF-Token Header
    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
&lt;/IfModule&gt;</pre>
        </div>
        <?php else: ?>
        <div class="success">
            <h3>✅ .htaccess File Exists</h3>
            <p>File .htaccess sudah ada. Jika routing masih error 404:</p>
            <ol>
                <li>Pastikan mod_rewrite enabled di hosting</li>
                <li>Check file permissions (.htaccess should be 644)</li>
                <li>Contact hosting support if needed</li>
            </ol>
        </div>
        <?php endif; ?>

        <div class="warning">
            <h3>⚠️ Alternative Solutions if mod_rewrite not available:</h3>
            <ol>
                <li><strong>Use index.php in URLs:</strong>
                    <ul>
                        <li>Instead of: <code>https://lapangkuy.site/login</code></li>
                        <li>Use: <code>https://lapangkuy.site/index.php/login</code></li>
                    </ul>
                </li>
                <li><strong>Configure Laravel for shared hosting limitations</strong></li>
                <li><strong>Contact hosting provider</strong> about URL rewriting support</li>
            </ol>
        </div>

        <h2>📋 Troubleshooting Steps</h2>
        <div class="info">
            <h4>If routes still return 404 after .htaccess fix:</h4>
            <ol>
                <li><strong>Clear Laravel cache:</strong>
                    <pre>php artisan route:clear
php artisan config:clear
php artisan cache:clear</pre>
                </li>
                <li><strong>Check Laravel routes exist:</strong>
                    <pre>php artisan route:list</pre>
                </li>
                <li><strong>Verify file permissions:</strong>
                    <ul>
                        <li>.htaccess: 644</li>
                        <li>index.php: 644</li>
                        <li>Directories: 755</li>
                    </ul>
                </li>
                <li><strong>Test with different routes</strong></li>
                <li><strong>Check hosting provider documentation</strong> for Laravel-specific requirements</li>
            </ol>
        </div>

        <div class="error">
            <strong>🔒 Security Note:</strong> Delete this test file (test-rewrite.php) after completing your routing tests to prevent unauthorized access to server information.
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <small>LapangKuy URL Rewrite Diagnostic Tool | <?php echo date('Y-m-d H:i:s'); ?></small>
        </div>
    </div>
</body>
</html>
