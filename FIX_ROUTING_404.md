# 🚨 Laravel Routing Error 404 - URL Rewriting Problem

## ❌ **Error yang Terjadi:**
- **Homepage (/)** → ✅ Bisa diakses
- **Routes lain (/login, /register, dll)** → ❌ Error 404 Not Found

## 🔍 **Root Cause:**
File `.htaccess` tidak ada atau tidak benar di **document root hosting** (`public_html/`)

## 🛠️ **Solusi Langsung:**

### **STEP 1: Cek File .htaccess di Hosting**
Via cPanel File Manager, pastikan ada file `.htaccess` di folder `public_html/`

### **STEP 2: Create/Update .htaccess di public_html/**
File `.htaccess` harus ada di **document root** (`public_html/`) dengan content:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

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
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options SAMEORIGIN
    Header always set X-XSS-Protection "1; mode=block"
</IfModule>

# Hide sensitive files
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "\.(env|log|md)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Prevent direct access to Laravel directories
RedirectMatch 404 ^/?(app|bootstrap|config|database|resources|routes|storage|tests|vendor)/.*$
```

### **STEP 3: Test mod_rewrite**
Beberapa shared hosting memiliki batasan mod_rewrite. Test dengan URL:
- `https://lapangkuy.site/` → Homepage (should work)
- `https://lapangkuy.site/login` → Login page (should work after fix)

## 🔧 **Diagnostic Tool:**

Mari saya buat tool untuk test URL rewriting:

```php
<?php
// test-rewrite.php - Upload ke public_html untuk test mod_rewrite
// URL: https://lapangkuy.site/test-rewrite.php?test=rewrite

if (!isset($_GET['test']) || $_GET['test'] !== 'rewrite') {
    die('Access denied');
}
?><!DOCTYPE html>
<html>
<head>
    <title>URL Rewrite Test - LapangKuy</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; background: #d4edda; padding: 10px; margin: 10px 0; }
        .error { color: red; background: #f8d7da; padding: 10px; margin: 10px 0; }
        .warning { color: orange; background: #fff3cd; padding: 10px; margin: 10px 0; }
        .info { color: blue; background: #d1ecf1; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>🔧 URL Rewrite & mod_rewrite Test</h1>
    
    <h2>📄 .htaccess File Check</h2>
    <?php
    if (file_exists('.htaccess')) {
        echo "<div class='success'>✅ .htaccess file exists</div>";
        
        $htaccessContent = file_get_contents('.htaccess');
        $checks = [
            'RewriteEngine On' => 'URL Rewriting Enabled',
            'RewriteRule.*index\.php' => 'Front Controller Pattern',
            'mod_rewrite' => 'mod_rewrite Module Check'
        ];
        
        foreach ($checks as $pattern => $description) {
            if (preg_match("/$pattern/i", $htaccessContent)) {
                echo "<div class='success'>✅ $description: Found</div>";
            } else {
                echo "<div class='warning'>⚠️ $description: Missing</div>";
            }
        }
        
        echo "<h3>Current .htaccess Content:</h3>";
        echo "<div class='info'><pre>" . htmlspecialchars($htaccessContent) . "</pre></div>";
        
    } else {
        echo "<div class='error'>❌ .htaccess file not found!</div>";
        echo "<div class='warning'>This is the main cause of routing 404 errors.</div>";
    }
    ?>
    
    <h2>🧪 mod_rewrite Test</h2>
    <div class='info'>
        <p><strong>Server Software:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></p>
        <p><strong>mod_rewrite Status:</strong> 
        <?php
        if (function_exists('apache_get_modules')) {
            $modules = apache_get_modules();
            if (in_array('mod_rewrite', $modules)) {
                echo "<span style='color:green'>✅ Enabled</span>";
            } else {
                echo "<span style='color:red'>❌ Not Available</span>";
            }
        } else {
            echo "<span style='color:orange'>⚠️ Cannot detect (shared hosting)</span>";
        }
        ?>
        </p>
    </div>
    
    <h2>🔍 Test URLs</h2>
    <div class='info'>
        <p>Test these URLs after fixing .htaccess:</p>
        <ul>
            <li><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/" target="_blank">Homepage: https://<?php echo $_SERVER['HTTP_HOST']; ?>/</a></li>
            <li><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/login" target="_blank">Login: https://<?php echo $_SERVER['HTTP_HOST']; ?>/login</a></li>
            <li><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/register" target="_blank">Register: https://<?php echo $_SERVER['HTTP_HOST']; ?>/register</a></li>
            <li><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/lapangan" target="_blank">Lapangan: https://<?php echo $_SERVER['HTTP_HOST']; ?>/lapangan</a></li>
        </ul>
    </div>
    
    <h2>🚀 Fix Steps</h2>
    <div class='warning'>
        <h3>If .htaccess is missing:</h3>
        <ol>
            <li>Create .htaccess file in public_html/</li>
            <li>Copy the content from Laravel public/.htaccess</li>
            <li>Add security headers (optional)</li>
            <li>Test URL routing</li>
        </ol>
        
        <h3>If .htaccess exists but URLs still 404:</h3>
        <ol>
            <li>Check mod_rewrite is enabled on server</li>
            <li>Contact hosting support about URL rewriting</li>
            <li>Try alternative routing methods</li>
        </ol>
        
        <h3>Alternative if mod_rewrite not available:</h3>
        <p>Use index.php in URLs: <code>https://lapangkuy.site/index.php/login</code></p>
    </div>
    
    <div class='error'>
        <strong>Security:</strong> Delete this file after testing!
    </div>
</body>
</html>
```

## 📋 **Quick Fix Checklist:**

### ✅ **Verified Working:**
- ✅ Homepage accessible
- ✅ Laravel loads correctly
- ✅ PHP and server working

### 🔄 **Need to Fix:**
- [ ] **Copy .htaccess** dari `public/` ke `public_html/`
- [ ] **Test URL routing** (/login, /register, etc.)
- [ ] **Verify mod_rewrite** enabled on hosting

### 🧪 **Testing URLs:**
After fixing .htaccess, test these URLs:
- `https://lapangkuy.site/` → Homepage ✅
- `https://lapangkuy.site/login` → Login page
- `https://lapangkuy.site/register` → Register page  
- `https://lapangkuy.site/lapangan` → Field listing

## 💡 **Pro Tips:**

1. **Hidden Files:** File `.htaccess` bisa tidak terlihat di File Manager. Enable "Show Hidden Files"
2. **Permission:** Set permission `.htaccess` ke 644
3. **mod_rewrite:** Jika shared hosting tidak support, contact support
4. **Alternative:** Gunakan `index.php` di URL jika rewrite tidak work

## 📞 **Next Actions:**

1. **Upload test-rewrite.php** ke public_html
2. **Access:** `https://lapangkuy.site/test-rewrite.php?test=rewrite`
3. **Copy .htaccess** dari Laravel public/ ke public_html/
4. **Test routing** dengan URLs Laravel
5. **Delete diagnostic files** setelah selesai

Masalah ini sangat umum dan mudah diperbaiki! Tinggal copy file `.htaccess` yang benar. 🚀
