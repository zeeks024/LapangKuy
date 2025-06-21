# Script Interaktif untuk Identifikasi Hosting Provider
# Jalankan script ini untuk membantu mengidentifikasi nameserver hosting Anda

Write-Host "=== 🔍 Identifikasi Hosting Provider untuk lapangkuy.site ===" -ForegroundColor Green
Write-Host ""

# Fungsi untuk menampilkan nameserver berdasarkan pilihan
function Show-Nameservers {
    param([string]$Provider, [string]$NS1, [string]$NS2)
    
    Write-Host "✅ Nameserver untuk $Provider:" -ForegroundColor Green
    Write-Host "   Primary NS:   $NS1" -ForegroundColor Yellow
    Write-Host "   Secondary NS: $NS2" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "📋 Copy nameserver ini ke domain registrar Anda!" -ForegroundColor Cyan
    Write-Host ""
}

# Database hosting providers Indonesia
$hostingProviders = @{
    "1" = @{Name="Niagahoster"; NS1="ns1.niagahoster.com"; NS2="ns2.niagahoster.com"}
    "2" = @{Name="Hostinger"; NS1="ns1.dns-parking.com"; NS2="ns2.dns-parking.com"}
    "3" = @{Name="DomaiNesia"; NS1="ns1.domainesia.com"; NS2="ns2.domainesia.com"}
    "4" = @{Name="IDCloudHost"; NS1="ns1.idcloudhost.com"; NS2="ns2.idcloudhost.com"}
    "5" = @{Name="Dewaweb"; NS1="ns1.dewaweb.com"; NS2="ns2.dewaweb.com"}
    "6" = @{Name="Jagoan Hosting"; NS1="ns1.jagoanhosting.com"; NS2="ns2.jagoanhosting.com"}
    "7" = @{Name="Rumahweb"; NS1="ns1.rumahweb.com"; NS2="ns2.rumahweb.com"}
    "8" = @{Name="CloudKilat"; NS1="ns1.cloudkilat.com"; NS2="ns2.cloudkilat.com"}
    "9" = @{Name="HostingMurah"; NS1="ns1.hostingmurah.com"; NS2="ns2.hostingmurah.com"}
    "10" = @{Name="Ardetamedia"; NS1="ns1.ardetamedia.com"; NS2="ns2.ardetamedia.com"}
}

Write-Host "Pilih hosting provider Anda:" -ForegroundColor Cyan
Write-Host ""

foreach ($key in $hostingProviders.Keys | Sort-Object) {
    $provider = $hostingProviders[$key]
    Write-Host "$key. $($provider.Name)" -ForegroundColor White
}

Write-Host "11. Lainnya (akan ditanyakan manual)" -ForegroundColor White
Write-Host "12. Tidak tahu / Perlu bantuan cari" -ForegroundColor White
Write-Host ""

do {
    $choice = Read-Host "Masukkan pilihan (1-12)"
    
    if ($hostingProviders.ContainsKey($choice)) {
        $selected = $hostingProviders[$choice]
        Show-Nameservers -Provider $selected.Name -NS1 $selected.NS1 -NS2 $selected.NS2
        
        # Berikan instruksi lengkap
        Write-Host "🚀 LANGKAH SELANJUTNYA:" -ForegroundColor Green
        Write-Host "1. Login ke domain registrar tempat beli domain lapangkuy.site" -ForegroundColor White
        Write-Host "2. Cari menu 'DNS Management' atau 'Nameservers'" -ForegroundColor White
        Write-Host "3. Ganti nameserver dengan yang di atas" -ForegroundColor White
        Write-Host "4. Save perubahan" -ForegroundColor White
        Write-Host "5. Tunggu 2-48 jam untuk DNS propagation" -ForegroundColor White
        Write-Host ""
        Write-Host "📧 Jika ragu, cek email welcome dari $($selected.Name) untuk konfirmasi" -ForegroundColor Yellow
        
        $valid = $true
    }
    elseif ($choice -eq "11") {
        Write-Host ""
        Write-Host "=== Input Manual Nameserver ===" -ForegroundColor Cyan
        $providerName = Read-Host "Nama hosting provider Anda"
        $ns1 = Read-Host "Primary nameserver (ns1)"
        $ns2 = Read-Host "Secondary nameserver (ns2)"
        
        if ($ns1 -and $ns2) {
            Show-Nameservers -Provider $providerName -NS1 $ns1 -NS2 $ns2
        } else {
            Write-Host "❌ Nameserver tidak boleh kosong!" -ForegroundColor Red
            continue
        }
        $valid = $true
    }
    elseif ($choice -eq "12") {
        Write-Host ""
        Write-Host "=== 🆘 Bantuan Mencari Nameserver ===" -ForegroundColor Yellow
        Write-Host ""
        Write-Host "Untuk membantu Anda, mohon berikan informasi:" -ForegroundColor Cyan
        Write-Host "1. Dari website mana Anda beli hosting?" -ForegroundColor White
        Write-Host "2. URL login cPanel hosting (contoh: cpanel.hostingname.com)" -ForegroundColor White
        Write-Host "3. Apakah ada email welcome dari hosting provider?" -ForegroundColor White
        Write-Host ""
        Write-Host "💡 Cara cepat:" -ForegroundColor Green
        Write-Host "- Cek email welcome dari hosting" -ForegroundColor White
        Write-Host "- Login ke cPanel dan cari 'Server Information'" -ForegroundColor White
        Write-Host "- Hubungi support hosting dan tanya nameserver" -ForegroundColor White
        Write-Host ""
        
        $cpanelUrl = Read-Host "URL cPanel hosting Anda (opsional, tekan Enter jika tidak tahu)"
        
        if ($cpanelUrl) {
            # Coba ekstrak dari URL cPanel
            $domain = ($cpanelUrl -replace "https?://", "" -replace "cpanel\.", "" -replace ":2083", "" -replace "/.*", "")
            if ($domain) {
                Write-Host ""
                Write-Host "💡 Berdasarkan URL cPanel, kemungkinan nameserver:" -ForegroundColor Yellow
                Write-Host "   ns1.$domain" -ForegroundColor Cyan
                Write-Host "   ns2.$domain" -ForegroundColor Cyan
                Write-Host ""
                Write-Host "⚠️  Konfirmasi dengan hosting support atau cek email welcome!" -ForegroundColor Red
            }
        }
        
        Write-Host ""
        Write-Host "📞 Hubungi support hosting dengan pertanyaan:" -ForegroundColor Green
        Write-Host '"Saya perlu nameserver untuk domain lapangkuy.site"' -ForegroundColor Gray
        
        $valid = $true
    }
    else {
        Write-Host "❌ Pilihan tidak valid! Masukkan angka 1-12" -ForegroundColor Red
        $valid = $false
    }
} while (-not $valid)

Write-Host ""
Write-Host "=== 📈 Monitor DNS Propagation ===" -ForegroundColor Green
Write-Host "Setelah set nameserver, gunakan command ini untuk monitor:" -ForegroundColor White
Write-Host ".\monitor-dns.ps1 -Domain 'lapangkuy.site'" -ForegroundColor Gray
Write-Host ""
Write-Host "Atau test manual:" -ForegroundColor White
Write-Host "nslookup lapangkuy.site" -ForegroundColor Gray
Write-Host "ping lapangkuy.site" -ForegroundColor Gray
Write-Host ""
Write-Host "🌐 Online checker: https://dnschecker.org" -ForegroundColor Cyan
Write-Host ""
Write-Host "✅ Good luck dengan setup domain Anda! 🚀" -ForegroundColor Green
