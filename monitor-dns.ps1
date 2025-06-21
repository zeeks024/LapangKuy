# Script untuk Monitor DNS Propagation Domain lapangkuy.site
# Jalankan script ini untuk memantau status DNS domain Anda

param(
    [string]$Domain = "lapangkuy.site",
    [int]$Interval = 300,  # 5 menit
    [int]$MaxChecks = 48   # 4 jam total (48 * 5 menit)
)

Write-Host "=== DNS Propagation Monitor untuk $Domain ===" -ForegroundColor Green
Write-Host "Interval checking: $Interval detik ($($Interval/60) menit)" -ForegroundColor Yellow
Write-Host "Maximum checks: $MaxChecks (Total: $($MaxChecks * $Interval / 3600) jam)" -ForegroundColor Yellow
Write-Host ""

$checkCount = 0
$resolved = $false

do {
    $checkCount++
    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    
    Write-Host "[$timestamp] Check #$checkCount of $MaxChecks" -ForegroundColor Cyan
    
    try {
        # Test DNS resolution
        $dnsResult = Resolve-DnsName $Domain -ErrorAction Stop
        
        if ($dnsResult) {
            Write-Host "✅ DNS RESOLVED!" -ForegroundColor Green
            Write-Host "IP Address: $($dnsResult[0].IPAddress)" -ForegroundColor Green
            $resolved = $true
            
            # Test HTTP connection
            try {
                $httpTest = Test-NetConnection $Domain -Port 80 -InformationLevel Quiet
                if ($httpTest) {
                    Write-Host "✅ HTTP (Port 80) - Accessible" -ForegroundColor Green
                } else {
                    Write-Host "❌ HTTP (Port 80) - Not accessible" -ForegroundColor Red
                }
            } catch {
                Write-Host "❌ HTTP (Port 80) - Error: $($_.Exception.Message)" -ForegroundColor Red
            }
            
            # Test HTTPS connection
            try {
                $httpsTest = Test-NetConnection $Domain -Port 443 -InformationLevel Quiet
                if ($httpsTest) {
                    Write-Host "✅ HTTPS (Port 443) - Accessible" -ForegroundColor Green
                } else {
                    Write-Host "❌ HTTPS (Port 443) - Not accessible" -ForegroundColor Red
                }
            } catch {
                Write-Host "❌ HTTPS (Port 443) - Error: $($_.Exception.Message)" -ForegroundColor Red
            }
            
            break
        }
    } catch {
        Write-Host "❌ DNS not resolved yet: $($_.Exception.Message)" -ForegroundColor Red
    }
    
    if ($checkCount -lt $MaxChecks) {
        Write-Host "⏳ Waiting $($Interval/60) minutes for next check..." -ForegroundColor Yellow
        Write-Host ""
        Start-Sleep $Interval
    }
    
} while ($checkCount -lt $MaxChecks -and -not $resolved)

Write-Host ""
Write-Host "=== Final Results ===" -ForegroundColor Green

if ($resolved) {
    Write-Host "🎉 SUCCESS! Domain $Domain is now accessible" -ForegroundColor Green
    Write-Host "Total time: $($checkCount * $Interval / 60) minutes" -ForegroundColor Green
    
    Write-Host ""
    Write-Host "Next Steps:" -ForegroundColor Cyan
    Write-Host "1. Test website: http://$Domain" -ForegroundColor White
    Write-Host "2. Test website: https://$Domain" -ForegroundColor White
    Write-Host "3. Setup SSL certificate if needed" -ForegroundColor White
    Write-Host "4. Upload and configure Laravel application" -ForegroundColor White
} else {
    Write-Host "⚠️  Domain $Domain still not resolved after $($MaxChecks * $Interval / 3600) hours" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Possible Issues:" -ForegroundColor Red
    Write-Host "1. Nameserver not configured correctly" -ForegroundColor White
    Write-Host "2. DNS propagation taking longer than usual" -ForegroundColor White
    Write-Host "3. Domain not added to hosting cPanel" -ForegroundColor White
    Write-Host "4. Hosting configuration issue" -ForegroundColor White
    Write-Host ""
    Write-Host "Action Items:" -ForegroundColor Cyan
    Write-Host "1. Check nameserver configuration at domain registrar" -ForegroundColor White
    Write-Host "2. Verify domain is added in cPanel hosting" -ForegroundColor White
    Write-Host "3. Contact hosting support if needed" -ForegroundColor White
    Write-Host "4. Continue monitoring or try manual checks" -ForegroundColor White
}

Write-Host ""
Write-Host "Manual DNS Check Commands:" -ForegroundColor Yellow
Write-Host "nslookup $Domain" -ForegroundColor Gray
Write-Host "ping $Domain" -ForegroundColor Gray
Write-Host ""
Write-Host "Online DNS Propagation Checker:" -ForegroundColor Yellow
Write-Host "https://dnschecker.org" -ForegroundColor Gray
