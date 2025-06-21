# 🔍 Cara Mencari Nameserver Hosting Anda

## ❌ Error: "Gagal mendapatkan nameserver"

Script otomatis tidak bisa mendeteksi nameserver hosting Anda. Mari kita cari secara manual.

## 📧 Method 1: Cek Email dari Hosting Provider

### Langkah-langkah:
1. **Buka email** yang Anda gunakan saat mendaftar hosting
2. **Cari email dari hosting provider** dengan subject seperti:
   - "Welcome to [Hosting Name]"
   - "Account Setup Complete"
   - "Server Information"
   - "DNS/Nameserver Information"

3. **Cari informasi seperti:**
   ```
   Primary Nameserver: ns1.hostingprovider.com
   Secondary Nameserver: ns2.hostingprovider.com
   ```

## 🌐 Method 2: Login ke cPanel Hosting

### Langkah-langkah:
1. **Login ke cPanel** hosting Anda
2. **Cari informasi server** di dashboard utama
3. **Atau cari menu "Server Information"**
4. **Nameserver biasanya tertulis seperti:**
   ```
   ns1.yourhostingname.com
   ns2.yourhostingname.com
   ```

## 📞 Method 3: Hubungi Support Hosting

### Yang perlu ditanyakan:
- "Apa nameserver untuk domain saya?"
- "Saya perlu nameserver untuk domain lapangkuy.site"

### Contoh pesan support:
```
Subject: Nameserver Information Needed

Halo,

Saya baru beli hosting dan perlu nameserver untuk domain lapangkuy.site
Bisa minta informasi nameserver yang harus saya set di domain registrar?

Terima kasih.
```

## 🏢 Method 4: Identifikasi Berdasarkan Hosting Provider

### Hosting Provider Indonesia Populer:

#### **Niagahoster**
```
ns1.niagahoster.com
ns2.niagahoster.com
```

#### **Hostinger**
```
ns1.dns-parking.com
ns2.dns-parking.com
```

#### **DomaiNesia**
```
ns1.domainesia.com
ns2.domainesia.com
```

#### **IDCloudHost**
```
ns1.idcloudhost.com
ns2.idcloudhost.com
```

#### **Dewaweb**
```
ns1.dewaweb.com
ns2.dewaweb.com
```

#### **Jagoan Hosting**
```
ns1.jagoanhosting.com
ns2.jagoanhosting.com
```

#### **Rumahweb**
```
ns1.rumahweb.com
ns2.rumahweb.com
```

## ❓ Pertanyaan untuk Identifikasi

**Untuk membantu saya, mohon jawab:**

1. **Dari mana Anda beli hosting?** (nama website/perusahaan)
2. **Apakah ada email welcome dari hosting?**
3. **Bisa login ke cPanel hosting?**
4. **URL cPanel hosting seperti apa?** (contoh: cpanel.hostingname.com)

## 🚀 Setelah Mendapat Nameserver

### Langkah selanjutnya:
1. **Set nameserver di domain registrar**
2. **Tunggu DNS propagation (2-48 jam)**
3. **Test domain dengan:**
   ```powershell
   nslookup lapangkuy.site
   ping lapangkuy.site
   ```

## 📋 Template Set Nameserver

### Setelah dapat nameserver, set seperti ini:
```
Primary Nameserver: ns1.[hosting-anda].com
Secondary Nameserver: ns2.[hosting-anda].com
```

### Ganti [hosting-anda] dengan nama hosting provider Anda.

---

## 🆘 Bantuan Langsung

**Jika masih bingung, berikan informasi:**
- Nama hosting provider
- Email welcome hosting (screenshot/copy text)
- URL login cPanel

Saya akan bantu identifikasi nameserver yang tepat!
