# ✅ Error 500 Fix Checklist

## 🚨 IMMEDIATE ACTIONS (Do this NOW)

### [ ] 1. Upload Diagnostic Tools
- [ ] Login to cPanel File Manager
- [ ] Navigate to `public_html` folder
- [ ] Upload `quick-diagnostic.php`
- [ ] Upload `emergency-fix.php`
- [ ] Set file permissions to 644

### [ ] 2. Run Diagnostic
- [ ] Visit: https://lapangkuy.site/quick-diagnostic.php
- [ ] Take screenshot of results
- [ ] Note any RED error messages
- [ ] Look especially for:
  - [ ] Missing .env file
  - [ ] Missing APP_KEY
  - [ ] Permission errors
  - [ ] Missing vendor/autoload.php

### [ ] 3. Apply Emergency Fixes
Visit: https://lapangkuy.site/emergency-fix.php?password=lapangkuy2024fix

- [ ] Click "Create Minimal .env File"
- [ ] Click "Generate New APP_KEY" 
- [ ] Click "Fix Directory Permissions"
- [ ] Click "Clear Laravel Cache"
- [ ] Click "Create .htaccess File"

### [ ] 4. Update Database Settings
- [ ] Find your cPanel database details:
  - Database name: ________________
  - Username: ____________________
  - Password: ____________________

- [ ] Edit .env file in File Manager:
  - [ ] Update DB_DATABASE
  - [ ] Update DB_USERNAME  
  - [ ] Update DB_PASSWORD

### [ ] 5. Test Website
- [ ] Visit: https://lapangkuy.site
- [ ] Does homepage load? YES / NO
- [ ] If NO, run diagnostic again
- [ ] If YES, test login page
- [ ] Test booking page

## 🔧 TROUBLESHOOTING STEPS

### If Diagnostic Shows "Composer autoload missing":
- [ ] Check if vendor folder exists in lapangkuy_laravel/
- [ ] If missing, need to run `composer install`
- [ ] Contact hosting support for SSH access

### If Diagnostic Shows "Laravel bootstrap missing":
- [ ] Check if bootstrap/app.php exists
- [ ] Verify correct Laravel project structure
- [ ] May need to re-upload Laravel files

### If Diagnostic Shows "Permission errors":  
- [ ] In cPanel File Manager, select storage folder
- [ ] Right-click → Change Permissions
- [ ] Set to 755 (drwxr-xr-x)
- [ ] Apply to subdirectories
- [ ] Repeat for bootstrap/cache

### If Diagnostic Shows ".env missing":
- [ ] Use emergency fix to create .env
- [ ] Verify APP_KEY is set
- [ ] Update database credentials
- [ ] Set APP_DEBUG=false for production

## 📊 PROGRESS TRACKING

### Status Updates:
- **Started**: ________________ (time)
- **Diagnostic uploaded**: _______ (time)  
- **Emergency fixes applied**: _____ (time)
- **Database updated**: __________ (time)
- **Website working**: ___________ (time)

### Current Error Messages:
```
(Paste current error message here)
```

### Screenshots Taken:
- [ ] Diagnostic results
- [ ] cPanel database settings
- [ ] File Manager permissions
- [ ] Working website (when fixed)

## ⚠️ IMPORTANT REMINDERS

### Security:
- [ ] Delete diagnostic files after fixing
- [ ] Change emergency password
- [ ] Set APP_DEBUG=false
- [ ] Don't share database passwords

### After Success:
- [ ] Test all website features
- [ ] Test user registration/login
- [ ] Test booking system
- [ ] Test payment integration
- [ ] Update DNS if needed
- [ ] Set up SSL certificate

## 📞 SUPPORT CONTACTS

**Hosting Provider Support**: ________________
**cPanel Username**: ________________________  
**Domain**: lapangkuy.site
**Project**: LapangKuy Laravel Sports Booking

---
**NEXT STEPS AFTER FIXING ERROR 500:**
1. Complete application testing
2. Set up SSL certificate  
3. Configure email settings
4. Test Midtrans payment integration
5. Optimize for production
