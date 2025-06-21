# 🚀 Cara Set Nameserver Domain lapangkuy.site

## ⚠️ PENTING: Langkah Ini WAJIB Dilakukan Dulu!

Domain Anda tidak bisa diakses karena **nameserver belum diset**. Tanpa nameserver yang benar, domain tidak akan mengarah ke hosting Anda.

## 📋 Informasi yang Dibutuhkan

Sebelum mulai, siapkan informasi berikut:

### 1. **Login Domain Registrar**
- **Email/Username** untuk login ke tempat beli domain
- **Password** akun domain registrar
- **Website domain registrar** (contoh: namecheap.com, godaddy.com, dll)

### 2. **Nameserver Hosting Provider**
- **Nameserver 1** (ns1.yourhostingprovider.com)
- **Nameserver 2** (ns2.yourhostingprovider.com)

## 🔍 Cara Mencari Nameserver Hosting

### Opsi 1: Cek Email Welcome Hosting
- Cek email dari hosting provider saat pertama beli
- Biasanya ada informasi nameserver di email welcome

### Opsi 2: Login ke Control Panel Hosting
1. Login ke cPanel/hosting panel
2. Cari menu **"Account Information"** atau **"Server Information"**
3. Akan ada informasi nameserver

### Opsi 3: Contact Hosting Support
- Chat/email ke support hosting
- Tanya: "Apa nameserver untuk akun hosting saya?"

## 🛠️ Langkah Set Nameserver

### Step 1: Login ke Domain Registrar
1. **Buka website domain registrar** (tempat beli lapangkuy.site)
2. **Login** dengan akun Anda
3. **Cari domain lapangkuy.site** di dashboard

### Step 2: Akses DNS Management
Cari menu dengan nama seperti:
- **"DNS Management"**
- **"Nameservers"**
- **"DNS Settings"**
- **"Domain Management"**

### Step 3: Ubah Nameserver
1. **Pilih "Custom Nameservers"** (bukan parking/default)
2. **Masukkan nameserver hosting:**
   ```
   Nameserver 1: ns1.yourhostingprovider.com
   Nameserver 2: ns2.yourhostingprovider.com
   ```
3. **Save/Apply Changes**

### Step 4: Konfirmasi
- Akan ada notifikasi "Changes saved" atau similar
- Biasanya ada peringatan "Changes may take 24-48 hours to propagate"

## 📱 Contoh untuk Provider Populer

### Namecheap:
1. Login → Dashboard
2. Domain List → Manage (lapangkuy.site)
3. Nameservers → Custom DNS
4. Masukkan nameserver → Save

### GoDaddy:
1. Login → My Products
2. Domains → lapangkuy.site → Manage
3. Nameservers → Change → Custom
4. Masukkan nameserver → Save

### Cloudflare Registrar:
1. Login → Domain Registration
2. lapangkuy.site → Manage
3. Nameservers → Custom
4. Add nameservers → Save

## ⏰ Setelah Set Nameserver

### Waktu Propagation:
- **Minimum:** 2-4 jam
- **Normal:** 6-24 jam  
- **Maximum:** 48 jam

### Cara Cek Progress:
```powershell
# Test setiap beberapa jam
nslookup lapangkuy.site
ping lapangkuy.site

# Atau gunakan script monitoring
.\monitor-dns.ps1 -Domain "lapangkuy.site"
```

### Online Tools:
- **DNS Checker:** https://dnschecker.org
- **What's My DNS:** https://whatsmydns.net

## 🔧 Setelah DNS Resolve

Setelah domain bisa di-resolve, lanjut ke:

### 1. **Setup Domain di cPanel Hosting**
- Login ke cPanel
- Addon Domains → Add lapangkuy.site

### 2. **Upload Laravel Files**
- Upload project ke hosting
- Setup database dan .env

### 3. **Install SSL Certificate**
- Free SSL via Let's Encrypt di cPanel

## 🚨 Troubleshooting

### Jika Lupa Login Domain Registrar:
- Cek email konfirmasi saat beli domain
- Gunakan "Forgot Password" di website registrar
- Contact support registrar dengan proof of purchase

### Jika Tidak Tahu Nameserver Hosting:
- Cek email welcome dari hosting
- Login ke cPanel → Server Information
- Contact hosting support

### Jika Changes Tidak Save:
- Clear browser cache, coba lagi
- Coba browser lain
- Contact domain registrar support

## 📞 Contacts

### Domain Issues:
- **Domain Registrar Support** (prioritas utama)

### Hosting Issues:
- **Hosting Provider Support** (untuk info nameserver)

## ✅ Checklist

- [ ] **Login ke domain registrar berhasil** ✓
- [ ] **Domain lapangkuy.site ditemukan di dashboard** ✓
- [ ] **Nameserver hosting sudah didapat** ✓
- [ ] **Nameserver berhasil diubah** ✓
- [ ] **Changes saved/applied** ✓
- [ ] **Mulai monitoring DNS propagation** ✓

---

## 🎯 Next Steps

Setelah nameserver diset:

1. **Tunggu 2-6 jam** untuk propagasi awal
2. **Test domain** dengan tools yang sudah disiapkan
3. **Setup hosting** setelah DNS resolve
4. **Upload Laravel** dan konfigurasi

**📞 Update:** Segera setelah Anda set nameserver, beri tahu saya agar kita bisa mulai monitoring!

---

### 💡 TIPS:
- **Screenshot** setiap langkah untuk dokumentasi
- **Save** informasi nameserver untuk referensi
- **Bookmark** halaman DNS management untuk akses cepat
