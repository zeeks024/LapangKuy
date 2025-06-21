# 🚨 URGENT: Fix Error 500 - Step by Step

## Current Status
Your website is showing **Error 500 Internal Server Error**. This usually happens due to:
- Missing or incorrect .env file
- Missing APP_KEY in Laravel
- Wrong file permissions
- Missing .htaccess file
- Laravel cache issues

## IMMEDIATE ACTION REQUIRED

### Step 1: Upload Diagnostic Tools
1. **Log into cPanel File Manager**
2. **Navigate to public_html folder**
3. **Upload these files** from your local project:
   - `quick-diagnostic.php`
   - `emergency-fix.php`

### Step 2: Run Diagnostic
1. **Open your browser**
2. **Visit**: https://lapangkuy.site/quick-diagnostic.php
3. **Look for RED error messages** - these show the problem
4. **Take screenshot** and note the errors

### Step 3: Apply Emergency Fixes
1. **Visit**: https://lapangkuy.site/emergency-fix.php?password=lapangkuy2024fix
2. **Click buttons in this order**:
   - ✅ Create Minimal .env File
   - ✅ Generate New APP_KEY  
   - ✅ Fix Directory Permissions
   - ✅ Clear Laravel Cache
   - ✅ Create .htaccess File

### Step 4: Update Database Credentials
1. **In cPanel, find your database details**:
   - Database name: `lapangku_main` (or similar)
   - Username: `lapangku_admin` (or similar) 
   - Password: Your cPanel database password

2. **Edit .env file in File Manager**:
   ```
   DB_DATABASE=your_actual_database_name
   DB_USERNAME=your_actual_username  
   DB_PASSWORD=your_actual_password
   ```

### Step 5: Test Website
1. **Visit**: https://lapangkuy.site
2. **Check if homepage loads**
3. **If still error 500**, run diagnostic again

## Most Common Fixes

### Fix 1: Missing .env File
```bash
# The emergency fix will create this file
APP_KEY=base64:J8S7SFGSFgsdfgSDGsdgSDGsdgSDGsdgSDGsQ=
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=lapangku_main
DB_USERNAME=lapangku_admin
DB_PASSWORD=YourPassword
```

### Fix 2: Wrong File Permissions
```bash
# Set these permissions in cPanel File Manager:
storage/ → 755
bootstrap/cache/ → 755
```

### Fix 3: Missing .htaccess
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

## Emergency Contacts
- **cPanel Support**: Contact your hosting provider
- **Laravel Documentation**: https://laravel.com/docs

## After Fixing
1. **Delete diagnostic files** for security:
   - `quick-diagnostic.php`
   - `emergency-fix.php`
2. **Change emergency password** in emergency-fix.php
3. **Test all website features**

## What to Report Back
Please share:
1. **Screenshot** of diagnostic results
2. **Which fixes** you applied
3. **Current error message** (if any)
4. **cPanel database details** (name, username - NOT password)

---
**TIME IS CRITICAL** - The longer the site is down, the more it affects SEO and user trust. Please act immediately!
