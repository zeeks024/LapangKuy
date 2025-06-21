<?php
/**
 * Laravel APP_KEY Generator
 * Upload ke public_html jika tidak bisa generate via artisan
 * URL: https://lapangkuy.site/generate-app-key.php?generate=key
 * 
 * HAPUS FILE INI SETELAH MENDAPAT KEY!
 */

if (!isset($_GET['generate']) || $_GET['generate'] !== 'key') {
    die('Access denied');
}

function generateRandomKey($length = 32) {
    return base64_encode(random_bytes($length));
}

?><!DOCTYPE html>
<html>
<head>
    <title>Laravel APP_KEY Generator</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .key-box { background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; border: 2px solid #007bff; }
        .warning { color: red; background: #f8d7da; padding: 15px; border-radius: 5px; margin: 20px 0; }
        pre { background: #e9ecef; padding: 10px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>🔑 Laravel APP_KEY Generator</h1>
    
    <div class="key-box">
        <h3>Generated APP_KEY:</h3>
        <pre>APP_KEY=base64:<?php echo generateRandomKey(); ?></pre>
        
        <h3>Alternative Keys (choose any one):</h3>
        <pre>APP_KEY=base64:<?php echo generateRandomKey(); ?></pre>
        <pre>APP_KEY=base64:<?php echo generateRandomKey(); ?></pre>
    </div>
    
    <h3>Instructions:</h3>
    <ol>
        <li>Copy one of the APP_KEY values above</li>
        <li>Edit your .env file in hosting</li>
        <li>Replace the APP_KEY line with the generated one</li>
        <li>Save the .env file</li>
        <li>Test your website</li>
        <li><strong>Delete this file immediately for security!</strong></li>
    </ol>
    
    <div class="warning">
        <strong>Security Warning:</strong> Delete this file after getting your APP_KEY to prevent unauthorized access!
    </div>
    
    <p><small>Generated at: <?php echo date('Y-m-d H:i:s'); ?></small></p>
</body>
</html>
