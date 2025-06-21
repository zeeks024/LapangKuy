# 🔍 Helper Script - Identifikasi Hosting Provider & Nameserver

## 📋 Informasi yang Perlu Dikumpulkan

### 1. **Domain Registrar Information**
**Tempat beli domain lapangkuy.site:**
- [ ] **Nama perusahaan:** ________________
- [ ] **Website:** ________________
- [ ] **Email akun:** ________________
- [ ] **Username:** ________________

### 2. **Hosting Provider Information**  
**Tempat beli hosting/server:**
- [ ] **Nama perusahaan:** ________________
- [ ] **Website:** ________________
- [ ] **Email akun:** ________________
- [ ] **Username:** ________________

### 3. **Cek Email untuk Info Hosting**

#### Kata Kunci untuk Cari di Email:
- "Welcome to"
- "Nameserver"
- "DNS"
- "ns1" atau "ns2"
- "hosting account"
- "server information"

#### Template Pencarian Email:
```
From: [hosting_provider]
Subject: Welcome* OR Account* OR Server*
Body: nameserver OR ns1 OR DNS
```

## 🔧 Cara Identifikasi Nameserver

### Opsi 1: Cari di Email Welcome Hosting
Contoh email yang dicari:
```
Subject: Welcome to Your New Hosting Account
From: support@hostingprovider.com

Your nameservers are:
ns1.hostingprovider.com
ns2.hostingprovider.com
```

### Opsi 2: Login ke cPanel Hosting
1. **Buka cPanel URL** (biasanya dari email welcome)
2. **Login** dengan credentials hosting
3. **Cari "Server Information"** di sidebar atau dashboard
4. **Copy nameserver information**

### Opsi 3: Whois Lookup Hosting Provider
Jika tahu website hosting provider:
```powershell
nslookup hostingprovider.com
# Lihat hasil untuk identifikasi nameserver pattern
```

## 📱 Template Kontak Support

### Untuk Domain Registrar Support:
```
Subject: Need Help Setting Nameserver for lapangkuy.site

Hi,

I need help setting up nameserver for my domain lapangkuy.site.
I have hosting with [HOSTING_PROVIDER_NAME] and need to point 
my domain to their nameservers:

ns1.[HOSTING_PROVIDER].com
ns2.[HOSTING_PROVIDER].com

Can you please guide me how to change nameserver in my account?

Thank you.
```

### Untuk Hosting Provider Support:
```
Subject: Need Nameserver Information for My Account

Hi,

I need the nameserver information for my hosting account.
I want to point my domain lapangkuy.site to your servers.

My account details:
- Email: [YOUR_EMAIL]
- Domain: lapangkuy.site

Please provide:
1. Primary nameserver (ns1)
2. Secondary nameserver (ns2)

Thank you.
```

## 🏢 Common Hosting Provider Nameservers

### Indonesia Hosting Providers:
```
Hostinger Indonesia:
- ns1.dns-parking.com
- ns2.dns-parking.com

Niagahoster:
- ns1.niagahoster.com
- ns2.niagahoster.com

Dewaweb:
- ns1.dewaweb.com
- ns2.dewaweb.com

Qwords:
- ns1.qwords.com  
- ns2.qwords.com

IDCloudHost:
- ns1.idcloudhost.com
- ns2.idcloudhost.com
```

### International Hosting Providers:
```
cPanel/WHM Generic:
- ns1.[domain].com
- ns2.[domain].com

Hostgator:
- ns1.hostgator.com
- ns2.hostgator.com

Bluehost:
- ns1.bluehost.com
- ns2.bluehost.com

SiteGround:
- ns1.siteground.net
- ns2.siteground.net
```

## 📞 Steps untuk Identifikasi

### Step 1: Identifikasi Hosting Provider
1. **Cek email** saat beli hosting
2. **Cek billing/invoice** hosting
3. **Login ke hosting panel** jika masih ingat

### Step 2: Dapatkan Nameserver
1. **Cek email welcome** hosting
2. **Login cPanel** → Server Information
3. **Contact support** hosting

### Step 3: Identifikasi Domain Registrar  
1. **Cek email** saat beli domain
2. **Cek billing/invoice** domain
3. **Whois lookup:** 
   ```powershell
   # Ini mungkin tidak work karena domain belum resolve
   # Tapi coba saja
   whois lapangkuy.site
   ```

### Step 4: Set Nameserver
Ikuti panduan di `CARA_SET_NAMESERVER.md`

## 🔍 Quick Check Script

Jalankan ini untuk test beberapa nameserver umum:

```powershell
# Test beberapa nameserver umum Indonesia
$commonNS = @(
    "ns1.niagahoster.com",
    "ns1.hostinger.com", 
    "ns1.dewaweb.com",
    "ns1.qwords.com",
    "ns1.idcloudhost.com"
)

foreach ($ns in $commonNS) {
    Write-Host "Testing $ns..." -ForegroundColor Yellow
    try {
        $result = nslookup $ns
        if ($result) {
            Write-Host "✅ $ns - Active" -ForegroundColor Green
        }
    } catch {
        Write-Host "❌ $ns - Not responding" -ForegroundColor Red
    }
}
```

## 📋 Information Collection Form

**Isi form ini saat mengumpulkan informasi:**

### Domain Information:
- **Domain:** lapangkuy.site
- **Registrar:** ________________
- **Registration Date:** ________________
- **Current Nameserver:** ________________

### Hosting Information:
- **Provider:** ________________
- **Package:** ________________
- **cPanel URL:** ________________
- **Nameserver 1:** ________________
- **Nameserver 2:** ________________

### Contact Information:
- **Domain Support:** ________________
- **Hosting Support:** ________________
- **Account Email:** ________________

---

## 🎯 Next Actions

Setelah dapat informasi:

1. **Set nameserver** menggunakan panduan `CARA_SET_NAMESERVER.md`
2. **Monitor DNS** dengan `monitor-dns.ps1`
3. **Setup hosting** setelah DNS resolve
4. **Deploy Laravel** mengikuti checklist

**💡 Tip:** Simpan semua informasi ini untuk referensi masa depan!
