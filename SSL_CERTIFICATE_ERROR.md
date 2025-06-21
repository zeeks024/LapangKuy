# 🔒 Masalah SSL Certificate lapangkuy.site - DEPTH_ZERO_SELF_SIGNED_CERT

## ❌ **Error Yang Terjadi:**
- **Error Code:** `DEPTH_ZERO_SELF_SIGNED_CERT`
- **Certificate:** CN=lapangkuy.site
- **Problem:** Self-signed certificate validation error

## 🔍 **Apa Artinya Error Ini:**

### **Self-Signed Certificate:**
- Certificate dibuat otomatis oleh hosting/cPanel
- Tidak diverifikasi oleh Certificate Authority (CA) yang trusted
- Browser tidak mengenali certificate ini sebagai valid
- Menyebabkan warning keamanan di browser

### **Dampak:**
- Website menampilkan warning "Not Secure"
- Browser modern memblokir akses HTTPS
- SEO ranking turun
- User experience buruk

## 🚨 **Status Saat Ini:**
Domain `lapangkuy.site` kemungkinan sudah bisa diakses tapi dengan masalah SSL!

Mari kita test apakah domain sudah resolve:

```powershell
# Test basic connectivity
ping lapangkuy.site
nslookup lapangkuy.site

# Test HTTP (tanpa SSL)
curl -I http://lapangkuy.site

# Test HTTPS (akan ada warning SSL)
curl -I https://lapangkuy.site --insecure
```

## 🛠️ **Solusi Lengkap:**

### **Solusi 1: Install Let's Encrypt SSL (GRATIS) - Recommended**

#### **Via cPanel SSL/TLS:**
1. **Login ke cPanel**
2. **Cari "SSL/TLS" atau "Let's Encrypt"**
3. **Let's Encrypt SSL:**
   - Domain: `lapangkuy.site`
   - Include www: ✅
   - Click "Issue"
4. **Tunggu 5-10 menit untuk processing**

#### **Via cPanel SSL/TLS - Alternative:**
1. **SSL/TLS** → **Manage SSL sites**
2. **Install an SSL Website**
3. **Browse Certificates** → **Let's Encrypt**
4. **Install Certificate**

### **Solusi 2: Force HTTPS Redirect (Setelah SSL Fixed)**

#### **Update di cPanel Domains:**
1. **Domains** → **lapangkuy.site**
2. **Force HTTPS Redirect** → **ON**
3. **Save**

#### **Manual via .htaccess:**
Edit file `.htaccess` di `/public_html/`:
```apache
# Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### **Solusi 3: Update Laravel Configuration**

Update file `.env`:
```env
APP_URL=https://lapangkuy.site
APP_ENV=production
APP_DEBUG=false

# Force HTTPS di Laravel
FORCE_HTTPS=true
```

## 🧪 **Testing DNS & Connectivity:**

Mari test apakah domain sudah accessible:
