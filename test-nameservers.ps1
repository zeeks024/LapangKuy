# Script untuk Test Nameserver Umum Indonesia
# Gunakan script ini untuk mengidentifikasi hosting provider Anda

Write-Host "=== Testing Common Indonesian Hosting Nameservers ===" -ForegroundColor Green
Write-Host "Script ini akan test nameserver umum untuk membantu identifikasi hosting provider Anda" -ForegroundColor Yellow
Write-Host ""

# Daftar nameserver hosting populer Indonesia
$hostingProviders = @{
    "Niagahoster" = @("ns1.niagahoster.com", "ns2.niagahoster.com")
    "Hostinger Indonesia" = @("ns1.hostinger.com", "ns2.hostinger.com")  
    "Dewaweb" = @("ns1.dewaweb.com", "ns2.dewaweb.com")
    "Qwords" = @("ns1.qwords.com", "ns2.qwords.com")
    "IDCloudHost" = @("ns1.idcloudhost.com", "ns2.idcloudhost.com")
    "Jagoan Hosting" = @("ns1.jagoanhosting.com", "ns2.jagoanhosting.com")
    "RumahWeb" = @("ns1.rumahweb.com", "ns2.rumahweb.com")
    "MasterWeb" = @("ns1.masterweb.net", "ns2.masterweb.net")
    "DomainRacer" = @("ns1.domainracer.com", "ns2.domainracer.com")
}

$activeProviders = @()

foreach ($provider in $hostingProviders.Keys) {
    Write-Host "Testing $provider..." -ForegroundColor Cyan
    
    $nsActive = $true
    foreach ($ns in $hostingProviders[$provider]) {
        try {
            $result = Resolve-DnsName $ns -Type A -ErrorAction Stop
            if ($result) {
                Write-Host "  ✅ $ns - Active (IP: $($result[0].IPAddress))" -ForegroundColor Green
            }
        } catch {
            Write-Host "  ❌ $ns - Not responding" -ForegroundColor Red
            $nsActive = $false
        }
    }
    
    if ($nsActive) {
        $activeProviders += $provider
        Write-Host "  🎯 $provider nameservers are ACTIVE" -ForegroundColor Green
    }
    
    Write-Host ""
}

Write-Host "=== Results Summary ===" -ForegroundColor Green

if ($activeProviders.Count -gt 0) {
    Write-Host "Active hosting providers found:" -ForegroundColor Yellow
    foreach ($provider in $activeProviders) {
        Write-Host "✅ $provider" -ForegroundColor Green
        Write-Host "   Nameservers: $($hostingProviders[$provider] -join ', ')" -ForegroundColor Gray
    }
} else {
    Write-Host "❌ No active nameservers found from common Indonesian providers" -ForegroundColor Red
}

Write-Host ""
Write-Host "=== Next Steps ===" -ForegroundColor Cyan

if ($activeProviders.Count -gt 0) {
    Write-Host "1. Check if any of the active providers above is your hosting provider" -ForegroundColor White
    Write-Host "2. If you find your provider, use their nameservers to set in domain registrar" -ForegroundColor White
    Write-Host "3. Follow the guide in CARA_SET_NAMESERVER.md" -ForegroundColor White
} else {
    Write-Host "1. Check your email for hosting welcome message" -ForegroundColor White
    Write-Host "2. Login to your hosting cPanel to find nameserver information" -ForegroundColor White
    Write-Host "3. Contact your hosting provider support for nameserver details" -ForegroundColor White
}

Write-Host ""
Write-Host "=== Manual Commands ===" -ForegroundColor Yellow
Write-Host "To test specific nameserver manually:" -ForegroundColor Gray
Write-Host "nslookup ns1.yourhostingprovider.com" -ForegroundColor Gray
Write-Host ""
Write-Host "To test domain after setting nameserver:" -ForegroundColor Gray
Write-Host "nslookup lapangkuy.site" -ForegroundColor Gray
Write-Host "ping lapangkuy.site" -ForegroundColor Gray

Write-Host ""
Write-Host "📧 Don't forget to check your email for hosting welcome messages!" -ForegroundColor Yellow
Write-Host "🔧 Use IDENTIFIKASI_HOSTING.md for detailed guidance" -ForegroundColor Yellow
