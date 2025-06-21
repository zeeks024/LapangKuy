# 🚨 Troubleshooting Domain lapangkuy.site Tidak Bisa Diakses

## 📊 Hasil Diagnosis

**Status:** ❌ Domain tidak dapat diakses  
**Masalah:** DNS resolution failed  
**Error:** Server can't find lapangkuy.site

## 🔍 Kemungkinan Penyebab & Solusi

### 1. **DNS Belum Terpropagasi** (Paling Umum)
DNS propagation bisa memakan waktu 24-48 jam setelah setup.

**Solusi:**
- Tunggu hingga 48 jam setelah setup DNS
- Cek status DNS propagation di: https://dnschecker.org
- Masukkan domain `lapangkuy.site` untuk cek global propagation

### 2. **Nameserver Belum Dikonfigurasi**
Domain belum diarahkan ke nameserver hosting.

**Langkah Pengecekan:**
1. Login ke **Domain Registrar** (tempat beli domain)
2. Cari menu **DNS Management** atau **Nameservers**
3. Pastikan nameserver sudah diset ke hosting provider

**Contoh Nameserver:**
```
ns1.hostingprovider.com
ns2.hostingprovider.com
```

### 3. **Domain Belum Ditambahkan di cPanel**
Domain belum dikonfigurasi di hosting cPanel.

**Langkah Setup:**
1. Login ke **cPanel hosting**
2. Cari **Addon Domains** atau **Subdomains**
3. Tambahkan domain `lapangkuy.site`
4. Arahkan ke folder yang tepat

### 4. **SSL Certificate Belum Aktif**
Akses HTTPS gagal karena SSL belum diinstall.

**Solusi:**
- Coba akses dengan HTTP: http://lapangkuy.site
- Install SSL certificate di cPanel
- Gunakan Let's Encrypt (gratis) atau SSL berbayar

## 🛠️ Langkah-Langkah Penyelesaian

### Step 1: Cek Status Domain
```bash
# Cek whois domain
whois lapangkuy.site

# Cek DNS records
nslookup lapangkuy.site
dig lapangkuy.site
```

### Step 2: Konfigurasi di Domain Registrar
1. Login ke akun domain registrar
2. Pilih domain `lapangkuy.site`
3. Set **Nameservers** ke nameserver hosting Anda
4. Contoh:
   ```
   ns1.yourhostingprovider.com
   ns2.yourhostingprovider.com
   ```

### Step 3: Setup di cPanel Hosting
1. Login ke cPanel
2. **Addon Domains** → Add New Domain
3. **Domain Name:** `lapangkuy.site`
4. **Document Root:** `public_html/lapangkuy` (atau sesuai kebutuhan)
5. Klik **Add Domain**

### Step 4: Upload & Konfigurasi Laravel
1. Upload file Laravel ke folder yang sudah ditentukan
2. Copy isi folder `public/` ke document root
3. Edit `index.php` untuk path yang benar
4. Setup database dan file `.env`

### Step 5: Test Akses
1. Coba akses HTTP terlebih dahulu: `http://lapangkuy.site`
2. Jika berhasil, install SSL certificate
3. Test HTTPS: `https://lapangkuy.site`

## 🔧 Tools untuk Diagnostic

### Online Tools:
- **DNS Checker:** https://dnschecker.org
- **Domain Tools:** https://whois.domaintools.com
- **SSL Checker:** https://www.ssllabs.com/ssltest/

### Command Line Tools:
```powershell
# Windows PowerShell
nslookup lapangkuy.site
ping lapangkuy.site
Test-NetConnection lapangkuy.site -Port 80
Test-NetConnection lapangkuy.site -Port 443
```

## 📞 Yang Perlu Dihubungi

### Jika DNS Tidak Resolve:
1. **Domain Registrar** - untuk konfigurasi nameserver
2. **Hosting Provider** - untuk konfirmasi nameserver mereka

### Jika Domain Resolve tapi Website Error:
1. **Hosting Support** - untuk konfigurasi hosting
2. Cek error logs di cPanel

## ⏰ Timeline Normal Setup

1. **Domain Registration:** Instant
2. **Nameserver Setup:** 15 menit - 2 jam
3. **DNS Propagation:** 2-48 jam
4. **Website Setup:** 1-2 jam
5. **SSL Installation:** 15 menit - 1 jam

## 🚀 Checklist Setup Domain & Hosting

### Domain Registrar:
- [ ] Domain sudah dibeli dan aktif
- [ ] Nameserver sudah diset ke hosting provider
- [ ] DNS management sudah dikonfigurasi

### Hosting Provider:
- [ ] Domain sudah ditambahkan di cPanel
- [ ] Document root sudah ditentukan
- [ ] Database sudah dibuat
- [ ] SSL certificate sudah diinstall

### Laravel Application:
- [ ] File sudah diupload
- [ ] Public files sudah di document root
- [ ] .env sudah dikonfigurasi
- [ ] Database sudah diimport
- [ ] Permissions sudah diset

## 📋 Informasi yang Dibutuhkan

Untuk troubleshooting lebih lanjut, siapkan:
1. **Domain Registrar** yang digunakan
2. **Hosting Provider** yang digunakan
3. **Nameserver** dari hosting
4. **Screenshot error** yang muncul
5. **Timeline** kapan domain dibeli dan disetup

---

**Catatan:** Masalah DNS adalah yang paling umum pada setup domain baru. Biasanya terselesaikan dalam 24-48 jam setelah konfigurasi yang benar.
