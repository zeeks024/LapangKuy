<?php
/**
 * Error 500 Diagnostic Tool untuk LapangKuy Laravel
 * Upload file ini ke public_html dan akses via browser
 * URL: https://lapangkuy.site/error-diagnostic.php?diagnose=500
 * 
 * HAPUS FILE INI SETELAH ERROR DIPERBAIKI!
 */

// Security check
if (!isset($_GET['diagnose']) || $_GET['diagnose'] !== '500') {
    http_response_code(404);
    die('Page not found');
}

// Enable error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 500 Diagnostic - LapangKuy</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { color: #28a745; background: #d4edda; padding: 10px; border-radius: 4px; margin: 10px 0; border-left: 4px solid #28a745; }
        .error { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 4px; margin: 10px 0; border-left: 4px solid #dc3545; }
        .warning { color: #856404; background: #fff3cd; padding: 10px; border-radius: 4px; margin: 10px 0; border-left: 4px solid #ffc107; }
        .info { color: #0c5460; background: #d1ecf1; padding: 10px; border-radius: 4px; margin: 10px 0; border-left: 4px solid #17a2b8; }
        h1 { color: #333; }
        h2 { color: #666; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-top: 30px; }
        h3 { color: #777; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto; max-height: 400px; border: 1px solid #e9ecef; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f8f9fa; font-weight: bold; }
        .status-ok { background: #d4edda; }
        .status-warning { background: #fff3cd; }
        .status-error { background: #f8d7da; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Error 500 Diagnostic Tool - LapangKuy</h1>
        
        <div class="info">
            <strong>Diagnostic Time:</strong> <?php echo date('Y-m-d H:i:s'); ?><br>
            <strong>Domain:</strong> <?php echo $_SERVER['HTTP_HOST']; ?><br>
            <strong>Request URI:</strong> <?php echo $_SERVER['REQUEST_URI']; ?><br>
            <strong>User Agent:</strong> <?php echo $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'; ?>
        </div>

        <h2>📊 Server Environment Analysis</h2>
        <table>
            <tr>
                <th>Property</th>
                <th>Value</th>
                <th>Status</th>
            </tr>
            <tr class="<?php echo version_compare(PHP_VERSION, '8.2.0', '>=') ? 'status-ok' : 'status-warning'; ?>">
                <td><strong>PHP Version</strong></td>
                <td><?php echo PHP_VERSION; ?></td>
                <td><?php echo version_compare(PHP_VERSION, '8.2.0', '>=') ? '✅ Compatible' : '⚠️ May be too old'; ?></td>
            </tr>
            <tr>
                <td><strong>Server Software</strong></td>
                <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></td>
                <td>ℹ️ Info</td>
            </tr>
            <tr>
                <td><strong>Document Root</strong></td>
                <td><?php echo $_SERVER['DOCUMENT_ROOT']; ?></td>
                <td>ℹ️ Info</td>
            </tr>
            <tr>
                <td><strong>Script Path</strong></td>
                <td><?php echo __FILE__; ?></td>
                <td>ℹ️ Info</td>
            </tr>
            <tr class="<?php echo (int)ini_get('memory_limit') >= 256 ? 'status-ok' : 'status-warning'; ?>">
                <td><strong>Memory Limit</strong></td>
                <td><?php echo ini_get('memory_limit'); ?></td>
                <td><?php echo (int)ini_get('memory_limit') >= 256 ? '✅ Adequate' : '⚠️ May be low'; ?></td>
            </tr>
            <tr>
                <td><strong>Max Execution Time</strong></td>
                <td><?php echo ini_get('max_execution_time'); ?>s</td>
                <td>ℹ️ Info</td>
            </tr>
            <tr>
                <td><strong>Error Reporting</strong></td>
                <td><?php echo error_reporting(); ?></td>
                <td>ℹ️ Now Enabled</td>
            </tr>
        </table>

        <h2>📁 Critical Files & Structure Check</h2>
        <?php
        $criticalChecks = [
            [
                'path' => 'index.php',
                'description' => 'Laravel Entry Point',
                'critical' => true
            ],
            [
                'path' => '.htaccess',
                'description' => 'URL Rewrite Configuration',
                'critical' => true
            ],
            [
                'path' => '../lapangkuy_laravel/.env',
                'description' => 'Environment Configuration',
                'critical' => true
            ],
            [
                'path' => '../lapangkuy_laravel/vendor/autoload.php',
                'description' => 'Composer Autoloader',
                'critical' => true
            ],
            [
                'path' => '../lapangkuy_laravel/bootstrap/app.php',
                'description' => 'Laravel Application Bootstrap',
                'critical' => true
            ],
            [
                'path' => '../lapangkuy_laravel/artisan',
                'description' => 'Laravel Artisan CLI',
                'critical' => false
            ]
        ];
        
        foreach ($criticalChecks as $check) {
            $path = $check['path'];
            $description = $check['description'];
            $critical = $check['critical'];
            
            if (file_exists($path)) {
                $size = filesize($path);
                $perms = substr(sprintf('%o', fileperms($path)), -4);
                $readable = is_readable($path);
                
                $class = $readable ? 'success' : 'warning';
                $status = $readable ? "✅ Found & Readable" : "⚠️ Found but not readable";
                
                echo "<div class='$class'>";
                echo "<strong>$description:</strong> $status<br>";
                echo "<small>Path: $path | Size: {$size}B | Permissions: $perms</small>";
                echo "</div>";
            } else {
                $class = $critical ? 'error' : 'warning';
                $status = $critical ? "❌ MISSING (CRITICAL)" : "⚠️ Missing (Optional)";
                
                echo "<div class='$class'>";
                echo "<strong>$description:</strong> $status<br>";
                echo "<small>Expected at: $path</small>";
                echo "</div>";
            }
        }
        ?>

        <h2>🗂️ Directory Permissions Analysis</h2>
        <?php
        $directories = [
            '../lapangkuy_laravel/storage' => ['description' => 'Main Storage Directory', 'required_writable' => true],
            '../lapangkuy_laravel/storage/app' => ['description' => 'Application Storage', 'required_writable' => true],
            '../lapangkuy_laravel/storage/framework' => ['description' => 'Framework Storage', 'required_writable' => true],
            '../lapangkuy_laravel/storage/framework/cache' => ['description' => 'Cache Storage', 'required_writable' => true],
            '../lapangkuy_laravel/storage/framework/sessions' => ['description' => 'Session Storage', 'required_writable' => true],
            '../lapangkuy_laravel/storage/framework/views' => ['description' => 'Compiled Views', 'required_writable' => true],
            '../lapangkuy_laravel/storage/logs' => ['description' => 'Log Files', 'required_writable' => true],
            '../lapangkuy_laravel/bootstrap/cache' => ['description' => 'Bootstrap Cache', 'required_writable' => true]
        ];
        
        echo "<table>";
        echo "<tr><th>Directory</th><th>Status</th><th>Permissions</th><th>Writable</th></tr>";
        
        foreach ($directories as $dir => $info) {
            $description = $info['description'];
            $requiredWritable = $info['required_writable'];
            
            if (is_dir($dir)) {
                $perms = substr(sprintf('%o', fileperms($dir)), -4);
                $writable = is_writable($dir);
                
                if ($writable) {
                    $statusClass = 'status-ok';
                    $status = '✅ Exists';
                    $writableStatus = '✅ Yes';
                } else {
                    $statusClass = $requiredWritable ? 'status-error' : 'status-warning';
                    $status = '⚠️ Exists';
                    $writableStatus = '❌ No';
                }
            } else {
                $statusClass = 'status-error';
                $status = '❌ Missing';
                $perms = 'N/A';
                $writableStatus = '❌ N/A';
            }
            
            echo "<tr class='$statusClass'>";
            echo "<td><strong>$description</strong><br><small>$dir</small></td>";
            echo "<td>$status</td>";
            echo "<td>$perms</td>";
            echo "<td>$writableStatus</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        ?>

        <h2>⚙️ Environment File Deep Analysis</h2>
        <?php
        $envPath = '../lapangkuy_laravel/.env';
        if (file_exists($envPath)) {
            echo "<div class='success'>✅ .env file found</div>";
            
            $envContent = file_get_contents($envPath);
            $envSize = filesize($envPath);
            $envPerms = substr(sprintf('%o', fileperms($envPath)), -4);
            
            echo "<div class='info'>";
            echo "<strong>File Size:</strong> {$envSize} bytes<br>";
            echo "<strong>Permissions:</strong> $envPerms<br>";
            echo "<strong>Last Modified:</strong> " . date('Y-m-d H:i:s', filemtime($envPath));
            echo "</div>";
            
            // Parse environment variables
            $envLines = explode("\n", $envContent);
            $envVars = [];
            
            foreach ($envLines as $line) {
                $line = trim($line);
                if (empty($line) || strpos($line, '#') === 0) continue;
                
                if (strpos($line, '=') !== false) {
                    $parts = explode('=', $line, 2);
                    $key = trim($parts[0]);
                    $value = isset($parts[1]) ? trim($parts[1]) : '';
                    $envVars[$key] = $value;
                }
            }
            
            // Check critical environment variables
            $criticalEnvVars = [
                'APP_NAME' => ['description' => 'Application Name', 'required' => true],
                'APP_ENV' => ['description' => 'Environment', 'required' => true, 'expected' => 'production'],
                'APP_KEY' => ['description' => 'Application Key', 'required' => true, 'sensitive' => true],
                'APP_DEBUG' => ['description' => 'Debug Mode', 'required' => true, 'expected' => 'false'],
                'APP_URL' => ['description' => 'Application URL', 'required' => true],
                'DB_CONNECTION' => ['description' => 'Database Driver', 'required' => true, 'expected' => 'mysql'],
                'DB_HOST' => ['description' => 'Database Host', 'required' => true],
                'DB_PORT' => ['description' => 'Database Port', 'required' => true, 'expected' => '3306'],
                'DB_DATABASE' => ['description' => 'Database Name', 'required' => true],
                'DB_USERNAME' => ['description' => 'Database Username', 'required' => true],
                'DB_PASSWORD' => ['description' => 'Database Password', 'required' => true, 'sensitive' => true]
            ];
            
            echo "<h3>Environment Variables Status:</h3>";
            echo "<table>";
            echo "<tr><th>Variable</th><th>Description</th><th>Status</th><th>Value</th></tr>";
            
            foreach ($criticalEnvVars as $varName => $varInfo) {
                $description = $varInfo['description'];
                $required = $varInfo['required'];
                $expected = $varInfo['expected'] ?? null;
                $sensitive = $varInfo['sensitive'] ?? false;
                
                if (isset($envVars[$varName])) {
                    $value = $envVars[$varName];
                    $isEmpty = empty($value);
                    
                    // Display value (hide sensitive data)
                    if ($sensitive) {
                        $displayValue = $isEmpty ? '[EMPTY]' : '[SET]';
                    } else {
                        $displayValue = $isEmpty ? '[EMPTY]' : htmlspecialchars($value);
                    }
                    
                    // Determine status
                    if ($isEmpty && $required) {
                        $statusClass = 'status-error';
                        $status = '❌ Empty';
                    } elseif ($expected && $value !== $expected) {
                        $statusClass = 'status-warning';
                        $status = "⚠️ Unexpected (expected: $expected)";
                    } else {
                        $statusClass = 'status-ok';
                        $status = '✅ Set';
                    }
                } else {
                    $statusClass = $required ? 'status-error' : 'status-warning';
                    $status = $required ? '❌ Missing' : '⚠️ Not set';
                    $displayValue = '[NOT SET]';
                }
                
                echo "<tr class='$statusClass'>";
                echo "<td><strong>$varName</strong></td>";
                echo "<td>$description</td>";
                echo "<td>$status</td>";
                echo "<td>$displayValue</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            
        } else {
            echo "<div class='error'>";
            echo "<strong>❌ .env file not found!</strong><br>";
            echo "This is a critical error. Laravel cannot function without .env file.<br>";
            echo "Expected location: $envPath";
            echo "</div>";
        }
        ?>

        <h2>🧪 Laravel Bootstrap Test</h2>
        <?php
        echo "<div class='info'>Attempting to bootstrap Laravel application...</div>";
        
        $bootstrapErrors = [];
        $bootstrapSuccess = false;
        
        // Capture any output/errors during bootstrap test
        ob_start();
        
        try {
            // Test 1: Composer Autoloader
            if (file_exists('../lapangkuy_laravel/vendor/autoload.php')) {
                require_once '../lapangkuy_laravel/vendor/autoload.php';
                echo "<div class='success'>✅ Step 1: Composer autoloader loaded successfully</div>";
            } else {
                throw new Exception("Composer autoloader not found");
            }
            
            // Test 2: Laravel Application Bootstrap
            if (file_exists('../lapangkuy_laravel/bootstrap/app.php')) {
                $app = require_once '../lapangkuy_laravel/bootstrap/app.php';
                echo "<div class='success'>✅ Step 2: Laravel application bootstrap successful</div>";
                
                // Test 3: Basic App Methods
                if (isset($app) && is_object($app)) {
                    echo "<div class='success'>✅ Step 3: Laravel app instance created</div>";
                    
                    // Test environment
                    if (method_exists($app, 'environment')) {
                        $env = $app->environment();
                        echo "<div class='info'>Current Environment: <strong>$env</strong></div>";
                    }
                    
                    // Test if app is booted
                    if (method_exists($app, 'isBooted')) {
                        $booted = $app->isBooted();
                        echo "<div class='info'>Application Booted: " . ($booted ? 'Yes' : 'No') . "</div>";
                    }
                    
                    $bootstrapSuccess = true;
                } else {
                    throw new Exception("Laravel app instance not created properly");
                }
            } else {
                throw new Exception("Laravel bootstrap file not found");
            }
            
        } catch (Exception $e) {
            echo "<div class='error'>";
            echo "<strong>❌ Bootstrap failed:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
            echo "<strong>File:</strong> " . $e->getFile() . "<br>";
            echo "<strong>Line:</strong> " . $e->getLine();
            echo "</div>";
            $bootstrapErrors[] = $e->getMessage();
        } catch (Error $e) {
            echo "<div class='error'>";
            echo "<strong>❌ PHP Fatal Error:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
            echo "<strong>File:</strong> " . $e->getFile() . "<br>";
            echo "<strong>Line:</strong> " . $e->getLine();
            echo "</div>";
            $bootstrapErrors[] = $e->getMessage();
        }
        
        $output = ob_get_clean();
        echo $output;
        ?>

        <h2>📋 Error Logs Analysis</h2>
        <?php
        $logPaths = [
            '../lapangkuy_laravel/storage/logs/laravel.log' => 'Laravel Application Log',
            '../lapangkuy_laravel/storage/logs/' => 'Laravel Logs Directory'
        ];
        
        $foundLogs = false;
        
        foreach ($logPaths as $logPath => $description) {
            if (is_file($logPath) && is_readable($logPath)) {
                $foundLogs = true;
                echo "<div class='success'>✅ Found: $description</div>";
                
                $logContent = file_get_contents($logPath);
                $logSize = strlen($logContent);
                
                if ($logSize > 0) {
                    // Get last 20 lines
                    $logLines = explode("\n", $logContent);
                    $recentLines = array_slice($logLines, -20);
                    
                    echo "<div class='info'>";
                    echo "<strong>Log Size:</strong> " . number_format($logSize) . " bytes<br>";
                    echo "<strong>Recent entries (last 20 lines):</strong>";
                    echo "</div>";
                    
                    echo "<pre>" . htmlspecialchars(implode("\n", $recentLines)) . "</pre>";
                } else {
                    echo "<div class='warning'>Log file is empty</div>";
                }
                
                break; // Only show first found log
                
            } elseif (is_dir($logPath)) {
                $logFiles = glob($logPath . '*.log');
                if (!empty($logFiles)) {
                    $foundLogs = true;
                    echo "<div class='success'>✅ Found log files in: $description</div>";
                    
                    foreach ($logFiles as $logFile) {
                        $fileName = basename($logFile);
                        $fileSize = filesize($logFile);
                        echo "<div class='info'>• $fileName (" . number_format($fileSize) . " bytes)</div>";
                    }
                    
                    // Show content of most recent log
                    $latestLog = end($logFiles);
                    if (is_readable($latestLog)) {
                        $logContent = file_get_contents($latestLog);
                        $logLines = explode("\n", $logContent);
                        $recentLines = array_slice($logLines, -15);
                        
                        echo "<div class='info'><strong>Recent entries from " . basename($latestLog) . ":</strong></div>";
                        echo "<pre>" . htmlspecialchars(implode("\n", $recentLines)) . "</pre>";
                    }
                }
            }
        }
        
        if (!$foundLogs) {
            echo "<div class='warning'>⚠️ No Laravel log files found or accessible</div>";
        }
        ?>

        <h2>🚀 Recommended Fix Actions</h2>
        <div class="warning">
            <h3>Based on the diagnostic results above:</h3>
            
            <?php if (!$bootstrapSuccess): ?>
            <div class="error">
                <h4>🔴 Critical Issues Found:</h4>
                <ul>
                    <li><strong>Laravel Bootstrap Failed</strong> - This is causing the 500 error</li>
                    <?php foreach ($bootstrapErrors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <h4>Immediate Actions:</h4>
            <ol>
                <li><strong>Fix .env file:</strong> Ensure all required variables are properly set</li>
                <li><strong>Fix directory permissions:</strong>
                    <pre>chmod -R 755 ../lapangkuy_laravel/storage/
chmod -R 755 ../lapangkuy_laravel/bootstrap/cache/</pre>
                </li>
                <li><strong>Clear Laravel cache:</strong> Delete cached configuration files</li>
                <li><strong>Test database connection:</strong> Verify database credentials</li>
                <li><strong>Generate APP_KEY:</strong> If missing or corrupted</li>
                <li><strong>Check server error logs:</strong> Contact hosting support for server-side logs</li>
            </ol>
            
            <h4>If database connection is the issue:</h4>
            <ul>
                <li>Verify database exists in cPanel</li>
                <li>Check database user has proper permissions</li>
                <li>Test database credentials manually</li>
            </ul>
        </div>

        <div class="info">
            <h3>📞 Next Steps:</h3>
            <ol>
                <li>Address the issues identified above</li>
                <li>Upload corrected .env file</li>
                <li>Fix file/directory permissions via cPanel</li>
                <li>Test the website again</li>
                <li><strong>Delete this diagnostic file</strong> after fixing</li>
            </ol>
        </div>

        <div class="error">
            <strong>🔒 Security Warning:</strong> Delete this diagnostic file (error-diagnostic.php) immediately after fixing the error to prevent unauthorized access to sensitive server information.
        </div>

        <div style="margin-top: 30px; text-align: center; color: #666;">
            <small>LapangKuy Error 500 Diagnostic Tool | Generated: <?php echo date('Y-m-d H:i:s'); ?></small>
        </div>
    </div>
</body>
</html>
