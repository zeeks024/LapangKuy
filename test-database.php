<?php
/**
 * Database Connection Test Script untuk lapangkuy.site
 * Upload file ini ke public_html dan akses via browser
 * URL: https://lapangkuy.site/test-database.php?test=db
 * 
 * HAPUS FILE INI SETELAH TESTING SELESAI!
 */

// Security check
if(!isset($_GET['test']) || $_GET['test'] !== 'db') {
    http_response_code(404);
    die('Page not found');
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Test - LapangKuy</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { color: #28a745; background: #d4edda; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .error { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .warning { color: #856404; background: #fff3cd; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .info { color: #0c5460; background: #d1ecf1; padding: 10px; border-radius: 4px; margin: 10px 0; }
        h1 { color: #333; }
        code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏟️ LapangKuy - Database Connection Test</h1>
        
        <div class="info">
            <strong>Test Time:</strong> <?php echo date('Y-m-d H:i:s'); ?><br>
            <strong>Server:</strong> <?php echo $_SERVER['HTTP_HOST']; ?>
        </div>

        <?php
        // Informasi PHP dan Extensions
        echo "<h2>📋 PHP Information</h2>";
        echo "<div class='info'>";
        echo "<strong>PHP Version:</strong> " . PHP_VERSION . "<br>";
        echo "<strong>PDO Available:</strong> " . (extension_loaded('pdo') ? '✅ Yes' : '❌ No') . "<br>";
        echo "<strong>PDO MySQL:</strong> " . (extension_loaded('pdo_mysql') ? '✅ Yes' : '❌ No') . "<br>";
        echo "</div>";

        // Test berbagai kemungkinan database credentials
        echo "<h2>🔍 Testing Database Connections</h2>";
        
        $possibleConfigs = [
            [
                'name' => 'Config 1: Standard cPanel (laps3233_)',
                'host' => 'localhost',
                'dbname' => 'laps3233_lapangkuy',
                'username' => 'laps3233_lapangkuy',
                'password' => '' // Will need actual password
            ],
            [
                'name' => 'Config 2: Alternative naming',
                'host' => 'localhost', 
                'dbname' => 'laps3233_lapangkuy_db',
                'username' => 'laps3233_admin',
                'password' => ''
            ],
            [
                'name' => 'Config 3: Simple naming',
                'host' => 'localhost',
                'dbname' => 'lapangkuy',
                'username' => 'lapangkuy',
                'password' => ''
            ]
        ];

        // Note: Password perlu diisi manual
        echo "<div class='warning'>";
        echo "<strong>⚠️ Important:</strong> Database passwords need to be filled manually in this script or in .env file.";
        echo "</div>";

        // Test .env file if exists
        $envPath = '../lapangkuy_laravel/.env';
        if (file_exists($envPath)) {
            echo "<h3>📄 Current .env Configuration</h3>";
            $envContent = file_get_contents($envPath);
            
            // Extract database config from .env
            $dbConfig = [];
            $lines = explode("\n", $envContent);
            foreach ($lines as $line) {
                if (strpos($line, 'DB_') === 0) {
                    $parts = explode('=', $line, 2);
                    if (count($parts) === 2) {
                        $key = trim($parts[0]);
                        $value = trim($parts[1]);
                        $dbConfig[$key] = $value;
                    }
                }
            }
            
            if (!empty($dbConfig)) {
                echo "<div class='info'>";
                echo "<table>";
                foreach ($dbConfig as $key => $value) {
                    $displayValue = ($key === 'DB_PASSWORD' && !empty($value)) ? '[HIDDEN]' : $value;
                    echo "<tr><td><strong>$key</strong></td><td>$displayValue</td></tr>";
                }
                echo "</table>";
                echo "</div>";
                
                // Test connection dengan config dari .env
                if (isset($dbConfig['DB_HOST']) && isset($dbConfig['DB_DATABASE']) && isset($dbConfig['DB_USERNAME'])) {
                    echo "<h3>🧪 Testing .env Configuration</h3>";
                    
                    $host = $dbConfig['DB_HOST'];
                    $dbname = $dbConfig['DB_DATABASE'];
                    $username = $dbConfig['DB_USERNAME'];
                    $password = $dbConfig['DB_PASSWORD'] ?? '';
                    
                    try {
                        $dsn = "mysql:host=$host;dbname=$dbname";
                        $pdo = new PDO($dsn, $username, $password);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                        echo "<div class='success'>";
                        echo "<h4>✅ Database Connection Successful!</h4>";
                        echo "<strong>Host:</strong> $host<br>";
                        echo "<strong>Database:</strong> $dbname<br>";
                        echo "<strong>Username:</strong> $username<br>";
                        echo "</div>";
                        
                        // Test tables
                        echo "<h4>📊 Database Tables</h4>";
                        try {
                            $stmt = $pdo->query("SHOW TABLES");
                            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                            
                            if (count($tables) > 0) {
                                echo "<div class='success'>";
                                echo "<strong>Found " . count($tables) . " tables:</strong><br>";
                                echo "<ul>";
                                foreach ($tables as $table) {
                                    echo "<li>$table</li>";
                                }
                                echo "</ul>";
                                echo "</div>";
                                
                                // Test Laravel specific tables
                                $laravelTables = ['users', 'sessions', 'migrations', 'lapangan', 'bookings'];
                                $foundLaravelTables = array_intersect($laravelTables, $tables);
                                
                                if (count($foundLaravelTables) > 0) {
                                    echo "<div class='success'>";
                                    echo "<strong>✅ Laravel tables found:</strong> " . implode(', ', $foundLaravelTables);
                                    echo "</div>";
                                } else {
                                    echo "<div class='warning'>";
                                    echo "<strong>⚠️ Laravel tables not found.</strong> You may need to run migrations or import database.";
                                    echo "</div>";
                                }
                                
                            } else {
                                echo "<div class='warning'>";
                                echo "⚠️ Database is empty. Need to import data or run migrations.";
                                echo "</div>";
                            }
                        } catch (Exception $e) {
                            echo "<div class='error'>";
                            echo "❌ Error querying tables: " . $e->getMessage();
                            echo "</div>";
                        }
                        
                    } catch (PDOException $e) {
                        echo "<div class='error'>";
                        echo "<h4>❌ Database Connection Failed</h4>";
                        echo "<strong>Error:</strong> " . $e->getMessage() . "<br>";
                        echo "<strong>Config tested:</strong><br>";
                        echo "Host: $host<br>";
                        echo "Database: $dbname<br>";
                        echo "Username: $username<br>";
                        echo "Password: " . (empty($password) ? '[EMPTY]' : '[PROVIDED]') . "<br>";
                        echo "</div>";
                        
                        echo "<div class='info'>";
                        echo "<h4>🔧 Troubleshooting Steps:</h4>";
                        echo "<ol>";
                        echo "<li>Check database name, username, and password in cPanel</li>";
                        echo "<li>Ensure database user has ALL PRIVILEGES on the database</li>";
                        echo "<li>Verify database actually exists</li>";
                        echo "<li>Check if hostname should be 'localhost' or server IP</li>";
                        echo "</ol>";
                        echo "</div>";
                    }
                }
            }
        } else {
            echo "<div class='error'>";
            echo "❌ .env file not found at: $envPath";
            echo "</div>";
        }

        // Recommendations
        echo "<h2>🚀 Next Steps</h2>";
        echo "<div class='info'>";
        echo "<h4>If connection successful:</h4>";
        echo "<ol>";
        echo "<li>Delete this test file immediately</li>";
        echo "<li>Clear Laravel config cache: <code>php artisan config:clear</code></li>";
        echo "<li>Test your website functionality</li>";
        echo "<li>Install SSL certificate</li>";
        echo "</ol>";
        
        echo "<h4>If connection failed:</h4>";
        echo "<ol>";
        echo "<li>Check database credentials in cPanel MySQL Databases</li>";
        echo "<li>Update .env file with correct credentials</li>";
        echo "<li>Ensure database user has proper permissions</li>";
        echo "<li>Import database from localhost if needed</li>";
        echo "</ol>";
        echo "</div>";

        echo "<div class='error'>";
        echo "<strong>🔒 Security Warning:</strong> Delete this file (test-database.php) after completing your tests to prevent unauthorized access to your database information.";
        echo "</div>";
        ?>
    </div>
</body>
</html>
