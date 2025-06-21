# Script Diagnosis Lengkap Domain lapangkuy.site
# Memeriksa berbagai kemungkinan masalah domain

param(
    [string]$Domain = "lapangkuy.site",
    [switch]$Verbose
)

Write-Host "=== DIAGNOSIS DOMAIN: $Domain ===" -ForegroundColor Green
Write-Host "Tanggal check: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')" -ForegroundColor Yellow
Write-Host ""

# Function untuk test dengan timeout
function Test-DnsWithTimeout {
    param($Domain, $DnsServer, $TimeoutSeconds = 5)
    
    try {
        $job = Start-Job -ScriptBlock {
            param($d, $dns)
            Resolve-DnsName $d -Server $dns -ErrorAction Stop
        } -ArgumentList $Domain, $DnsServer
        
        $result = Wait-Job $job -Timeout $TimeoutSeconds
        if ($result) {
            $output = Receive-Job $job
            Remove-Job $job -Force
            return $output
        } else {
            Remove-Job $job -Force
            throw "Timeout after $TimeoutSeconds seconds"
        }
    } catch {
        return $null
    }
}

# 1. Test DNS Resolution dengan berbagai DNS server
Write-Host "🔍 1. TESTING DNS RESOLUTION" -ForegroundColor Cyan
Write-Host "─────────────────────────────" -ForegroundColor Gray

$dnsServers = @{
    "Google DNS 1" = "8.8.8.8"
    "Google DNS 2" = "8.8.4.4"  
    "Cloudflare 1" = "1.1.1.1"
    "Cloudflare 2" = "1.0.0.1"
    "Quad9" = "9.9.9.9"
    "OpenDNS" = "208.67.222.222"
    "System Default" = $null
}

$resolvedCount = 0
$totalTests = $dnsServers.Count

foreach ($dnsName in $dnsServers.Keys) {
    $dnsServer = $dnsServers[$dnsName]
    Write-Host "Testing $dnsName" -NoNewline
    
    if ($dnsServer) {
        Write-Host " ($dnsServer)..." -NoNewline
    } else {
        Write-Host "..." -NoNewline
    }
    
    try {
        if ($dnsServer) {
            $result = Test-DnsWithTimeout -Domain $Domain -DnsServer $dnsServer -TimeoutSeconds 3
        } else {
            $result = Resolve-DnsName $Domain -ErrorAction Stop
        }
        
        if ($result) {
            Write-Host " ✅ RESOLVED" -ForegroundColor Green
            if ($Verbose -and $result[0].IPAddress) {
                Write-Host "    IP: $($result[0].IPAddress)" -ForegroundColor Gray
            }
            $resolvedCount++
        } else {
            Write-Host " ❌ NOT RESOLVED" -ForegroundColor Red
        }
    } catch {
        Write-Host " ❌ ERROR: $($_.Exception.Message.Split('.')[0])" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "DNS Resolution Summary: $resolvedCount/$totalTests servers resolved" -ForegroundColor $(if ($resolvedCount -gt 0) { 'Green' } else { 'Red' })
Write-Host ""

# 2. Test Nameserver Query
Write-Host "🔍 2. CHECKING NAMESERVERS" -ForegroundColor Cyan
Write-Host "─────────────────────────────" -ForegroundColor Gray

try {
    $nsQuery = Resolve-DnsName $Domain -Type NS -ErrorAction Stop
    if ($nsQuery) {
        Write-Host "✅ Nameservers found:" -ForegroundColor Green
        foreach ($ns in $nsQuery) {
            if ($ns.NameHost) {
                Write-Host "   • $($ns.NameHost)" -ForegroundColor White
            }
        }
    }
} catch {
    Write-Host "❌ No nameservers found or error: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# 3. Test Connectivity
Write-Host "🔍 3. TESTING CONNECTIVITY" -ForegroundColor Cyan
Write-Host "─────────────────────────────" -ForegroundColor Gray

# Test ping
Write-Host "Ping test..." -NoNewline
try {
    $pingResult = Test-Connection $Domain -Count 1 -Quiet -ErrorAction Stop
    if ($pingResult) {
        Write-Host " ✅ REACHABLE" -ForegroundColor Green
    } else {
        Write-Host " ❌ NOT REACHABLE" -ForegroundColor Red
    }
} catch {
    Write-Host " ❌ FAILED: $($_.Exception.Message.Split('.')[0])" -ForegroundColor Red
}

# Test HTTP port
Write-Host "HTTP (port 80)..." -NoNewline
try {
    $httpTest = Test-NetConnection $Domain -Port 80 -InformationLevel Quiet -WarningAction SilentlyContinue
    if ($httpTest) {
        Write-Host " ✅ OPEN" -ForegroundColor Green
    } else {
        Write-Host " ❌ CLOSED" -ForegroundColor Red
    }
} catch {
    Write-Host " ❌ ERROR" -ForegroundColor Red
}

# Test HTTPS port
Write-Host "HTTPS (port 443)..." -NoNewline
try {
    $httpsTest = Test-NetConnection $Domain -Port 443 -InformationLevel Quiet -WarningAction SilentlyContinue
    if ($httpsTest) {
        Write-Host " ✅ OPEN" -ForegroundColor Green
    } else {
        Write-Host " ❌ CLOSED" -ForegroundColor Red
    }
} catch {
    Write-Host " ❌ ERROR" -ForegroundColor Red
}

Write-Host ""

# 4. Recommendations
Write-Host "📋 4. DIAGNOSIS & RECOMMENDATIONS" -ForegroundColor Cyan
Write-Host "─────────────────────────────────────" -ForegroundColor Gray

if ($resolvedCount -eq 0) {
    Write-Host "❌ CRITICAL: Domain tidak ter-resolve di semua DNS server" -ForegroundColor Red
    Write-Host ""
    Write-Host "Kemungkinan masalah:" -ForegroundColor Yellow
    Write-Host "1. 🔸 Domain baru - DNS belum propagasi (tunggu 2-48 jam)" -ForegroundColor White
    Write-Host "2. 🔸 Nameserver tidak aktif atau salah konfigurasi" -ForegroundColor White  
    Write-Host "3. 🔸 Domain belum ditambahkan di hosting cPanel" -ForegroundColor White
    Write-Host "4. 🔸 Ada masalah dengan status domain" -ForegroundColor White
    Write-Host ""
    Write-Host "Langkah selanjutnya:" -ForegroundColor Cyan
    Write-Host "1. ✅ Cek WHOIS domain: https://whois.net" -ForegroundColor White
    Write-Host "2. ✅ Verifikasi nameserver di domain registrar" -ForegroundColor White
    Write-Host "3. ✅ Pastikan domain sudah ditambah di cPanel hosting" -ForegroundColor White
    Write-Host "4. ✅ Hubungi support hosting jika perlu" -ForegroundColor White
    
} elseif ($resolvedCount -lt $totalTests / 2) {
    Write-Host "⚠️  WARNING: Domain hanya ter-resolve di beberapa DNS server" -ForegroundColor Yellow
    Write-Host "Kemungkinan DNS propagation masih dalam proses." -ForegroundColor White
    Write-Host "Monitor terus setiap beberapa jam." -ForegroundColor White
    
} else {
    Write-Host "✅ GOOD: Domain ter-resolve di mayoritas DNS server" -ForegroundColor Green
    Write-Host "DNS propagation berjalan dengan baik." -ForegroundColor White
}

Write-Host ""
Write-Host "📞 Support Resources:" -ForegroundColor Cyan
Write-Host "• Domain registrar support (untuk nameserver)" -ForegroundColor White
Write-Host "• Hosting provider support (untuk cPanel)" -ForegroundColor White
Write-Host "• DNS checker online: https://dnschecker.org" -ForegroundColor White
Write-Host "• WHOIS lookup: https://whois.net" -ForegroundColor White

Write-Host ""
Write-Host "💡 TIP: Simpan output ini dan jalankan lagi dalam beberapa jam untuk melihat progress." -ForegroundColor Yellow
