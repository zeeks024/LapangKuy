# 🎉 DOMAIN SUDAH BISA DIAKSES! - Fixing Database Connection Error

## ✅ **Good News!**
Domain `lapangkuy.site` sudah bisa diakses! DNS propagation sudah selesai.

## ❌ **Current Error:**
```
Illuminate\Database\QueryException
SQLSTATE[28000] [1045] Access denied for user 'admin'@'localhost' (using password: YES)
```

## 🔍 **Root Cause:**
File `.env` di hosting masih menggunakan kredensial database yang salah:
- **Current user:** `admin` (SALAH)
- **Seharusnya:** User database yang dibuat di cPanel hosting

## 🛠️ **Solusi Step-by-Step:**

### **Step 1: Cek Database di cPanel**
1. Login ke cPanel hosting
2. Buka **MySQL Databases**
3. Lihat database dan user yang sudah dibuat
4. Catat informasi berikut:
   - Database name (biasanya: `username_lapangkuy`)
   - Database user (biasanya: `username_lapangkuy`)
   - Database password

### **Step 2: Update File .env di Hosting**
Via File Manager cPanel, edit file `.env` dan update bagian database:

```env
# Database Configuration - UPDATE SESUAI CPANEL
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=laps3233_lapangkuy    # Ganti dengan nama database Anda
DB_USERNAME=laps3233_lapangkuy    # Ganti dengan username database Anda  
DB_PASSWORD=your_actual_password  # Ganti dengan password database Anda
```

### **Step 3: Clear Configuration Cache**
Jika ada SSH access atau Terminal di cPanel:
```bash
cd /home/username/lapangkuy_laravel
php artisan config:clear
php artisan config:cache
```

### **Step 4: Test Database Connection**
Buat file test database connection di hosting.

## 📋 **Template .env untuk Hosting:**

Mari saya buat template .env yang tepat untuk hosting cPanel:

```env
# LapangKuy Production Environment - cPanel Hosting
APP_NAME="LapangKuy"
APP_ENV=production
APP_KEY=base64:your_generated_key_here
APP_DEBUG=false
APP_URL=https://lapangkuy.site

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

# Database - UPDATE DENGAN KREDENSIAL CPANEL ANDA
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=laps3233_lapangkuy        # GANTI dengan database name Anda
DB_USERNAME=laps3233_lapangkuy        # GANTI dengan database username Anda
DB_PASSWORD=your_database_password    # GANTI dengan database password Anda

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

# Cache
CACHE_STORE=database
CACHE_PREFIX=lapangkuy_

# Mail Configuration (Update sesuai hosting)
MAIL_MAILER=smtp
MAIL_HOST=mail.lapangkuy.site
MAIL_PORT=587
MAIL_USERNAME=noreply@lapangkuy.site
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@lapangkuy.site
MAIL_FROM_NAME="LapangKuy"

# Midtrans - Production
MIDTRANS_SERVER_KEY=your_production_server_key
MIDTRANS_CLIENT_KEY=your_production_client_key
MIDTRANS_IS_PRODUCTION=true
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=error
LOG_DAYS=7

# Queue
QUEUE_CONNECTION=database

# Filesystem
FILESYSTEM_DISK=local

VITE_APP_NAME="LapangKuy"
```

## 🔧 **Database Test Script:**

Mari saya buat script untuk test koneksi database:

```php
<?php
// test-database.php - Upload file ini ke hosting untuk test database
// Akses via: https://lapangkuy.site/test-database.php?test=db

if (!isset($_GET['test']) || $_GET['test'] !== 'db') {
    die('Access denied');
}

echo "<h2>Database Connection Test</h2>";

// Database credentials dari .env
$host = 'localhost';
$dbname = 'laps3233_lapangkuy';    // GANTI sesuai database Anda
$username = 'laps3233_lapangkuy';  // GANTI sesuai username Anda
$password = 'your_password';       // GANTI sesuai password Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    echo "<p><strong>Host:</strong> $host</p>";
    echo "<p><strong>Database:</strong> $dbname</p>";
    echo "<p><strong>Username:</strong> $username</p>";
    
    // Test query
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Tables in database:</h3>";
    if (count($tables) > 0) {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'>⚠️ No tables found. Need to run migrations.</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database connection failed:</p>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
    
    echo "<h3>Troubleshooting:</h3>";
    echo "<ul>";
    echo "<li>Check database name, username, and password</li>";
    echo "<li>Ensure database user has proper permissions</li>";
    echo "<li>Verify database exists in cPanel</li>";
    echo "</ul>";
}

echo "<p><small>Delete this file after testing for security.</small></p>";
?>
```

## 📞 **Informasi yang Dibutuhkan:**

Untuk menyelesaikan masalah ini, saya butuh informasi:

1. **Database name** di cPanel (screenshot MySQL Databases)
2. **Database username** yang sudah dibuat
3. **Database password** yang digunakan
4. **Apakah database sudah diimport** dari localhost?

## 🚀 **Langkah Selanjutnya:**

1. **✅ Domain accessible** (DONE!)
2. **🔄 Fix .env database credentials** (DO NOW)
3. **🔄 Test database connection** (DO NOW)
4. **🔄 Import database jika belum** (IF NEEDED)
5. **🔄 Run migrations jika perlu** (IF NEEDED)
6. **🔄 Install SSL certificate** (RECOMMENDED)
7. **🔄 Test all functionalities** (FINAL)

## 💡 **Quick Fix Command:**

Jika Anda tahu kredensial database yang benar, langsung edit file `.env` di cPanel File Manager dan ganti:

```env
DB_DATABASE=your_actual_database_name
DB_USERNAME=your_actual_database_username  
DB_PASSWORD=your_actual_database_password
```

Kemudian clear cache Laravel (jika ada SSH):
```bash
php artisan config:clear
```

Website akan langsung bisa diakses setelah database credentials diperbaiki!
