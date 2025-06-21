# Script untuk Test DNS lapangkuy.site dari Berbagai DNS Server
# Script ini akan test DNS resolution dari berbagai provider DNS

param(
    [string]$Domain = "lapangkuy.site"
)

Write-Host "=== Testing DNS Resolution untuk $Domain ===" -ForegroundColor Green
Write-Host "Timestamp: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')" -ForegroundColor Yellow
Write-Host ""

# Daftar DNS servers untuk test
$dnsServers = @(
    @{Name="Google DNS Primary"; IP="8.8.8.8"},
    @{Name="Google DNS Secondary"; IP="8.8.4.4"},
    @{Name="Cloudflare DNS Primary"; IP="1.1.1.1"},
    @{Name="Cloudflare DNS Secondary"; IP="1.0.0.1"},
    @{Name="OpenDNS Primary"; IP="208.67.222.222"},
    @{Name="OpenDNS Secondary"; IP="208.67.220.220"},
    @{Name="Quad9 DNS"; IP="9.9.9.9"},
    @{Name="System Default DNS"; IP=""}
)

$resolvedCount = 0
$totalServers = $dnsServers.Count

foreach ($dns in $dnsServers) {
    Write-Host "Testing: $($dns.Name)" -ForegroundColor Cyan
    Write-Host "DNS Server: $($dns.IP)" -ForegroundColor Gray
    
    try {
        if ($dns.IP -eq "") {
            # Test dengan system default DNS
            $result = Resolve-DnsName $Domain -ErrorAction Stop
        } else {
            # Test dengan DNS server tertentu
            $result = Resolve-DnsName $Domain -Server $dns.IP -ErrorAction Stop
        }
        
        if ($result) {
            Write-Host "✅ RESOLVED!" -ForegroundColor Green
            Write-Host "   IP Address: $($result[0].IPAddress)" -ForegroundColor Green
            Write-Host "   Record Type: $($result[0].Type)" -ForegroundColor Green
            $resolvedCount++
        }
    } catch {
        Write-Host "❌ NOT RESOLVED" -ForegroundColor Red
        Write-Host "   Error: $($_.Exception.Message)" -ForegroundColor Red
    }
    
    Write-Host ""
}

# Summary
Write-Host "=== SUMMARY ===" -ForegroundColor Green
Write-Host "Domain: $Domain" -ForegroundColor Yellow
Write-Host "Resolved: $resolvedCount / $totalServers DNS servers" -ForegroundColor Yellow

$percentage = [math]::Round(($resolvedCount / $totalServers) * 100, 1)
Write-Host "Success Rate: $percentage%" -ForegroundColor Yellow

Write-Host ""

if ($resolvedCount -eq 0) {
    Write-Host "🔴 STATUS: DNS NOT PROPAGATED" -ForegroundColor Red
    Write-Host ""
    Write-Host "Kemungkinan penyebab:" -ForegroundColor Yellow
    Write-Host "• Nameserver belum diset dengan benar" -ForegroundColor White
    Write-Host "• DNS propagation masih berlangsung (tunggu 2-48 jam)" -ForegroundColor White
    Write-Host "• Domain belum dikonfigurasi di hosting" -ForegroundColor White
    Write-Host ""
    Write-Host "Yang harus dilakukan:" -ForegroundColor Cyan
    Write-Host "1. Verifikasi nameserver di domain registrar" -ForegroundColor White
    Write-Host "2. Pastikan domain sudah ditambah di cPanel" -ForegroundColor White
    Write-Host "3. Tunggu DNS propagation (bisa sampai 48 jam)" -ForegroundColor White
    Write-Host "4. Hubungi support hosting jika perlu" -ForegroundColor White
} elseif ($resolvedCount -lt $totalServers) {
    Write-Host "🟡 STATUS: PARTIAL DNS PROPAGATION" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "DNS sudah mulai resolve di beberapa server!" -ForegroundColor Green
    Write-Host "Propagation sedang berlangsung, tunggu beberapa jam lagi." -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Yang bisa dilakukan:" -ForegroundColor Cyan
    Write-Host "1. Test akses website (mungkin sudah bisa diakses)" -ForegroundColor White
    Write-Host "2. Lanjutkan setup website di hosting" -ForegroundColor White
    Write-Host "3. Monitor progress dengan script ini setiap beberapa jam" -ForegroundColor White
} else {
    Write-Host "🟢 STATUS: FULLY PROPAGATED" -ForegroundColor Green
    Write-Host ""
    Write-Host "🎉 Domain sudah fully resolved di semua DNS server!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Langkah selanjutnya:" -ForegroundColor Cyan
    Write-Host "1. Test akses website: http://$Domain" -ForegroundColor White
    Write-Host "2. Test akses website: https://$Domain" -ForegroundColor White
    Write-Host "3. Install SSL certificate jika belum" -ForegroundColor White
    Write-Host "4. Setup dan test semua fitur website" -ForegroundColor White
}

Write-Host ""
Write-Host "Online DNS Propagation Checkers:" -ForegroundColor Cyan
Write-Host "• https://dnschecker.org" -ForegroundColor Gray
Write-Host "• https://whatsmydns.net" -ForegroundColor Gray
Write-Host "• https://www.dns-lookup.com" -ForegroundColor Gray

Write-Host ""
Write-Host "Untuk monitoring otomatis setiap 10 menit:" -ForegroundColor Yellow
Write-Host ".\test-dns-multiple.ps1 | Tee-Object -FilePath 'dns-monitor.log' -Append" -ForegroundColor Gray
