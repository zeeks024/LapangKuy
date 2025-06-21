# LapangKuy Laravel - Preparation Script for cPanel Deployment
# Jalankan script ini sebelum upload ke hosting

param(
    [switch]$CreateZip,
    [switch]$CleanCache,
    [string]$ZipName = "lapangkuy_deployment.zip"
)

Write-Host "=== LapangKuy Laravel Deployment Preparation ===" -ForegroundColor Green
Write-Host "Current Directory: $(Get-Location)" -ForegroundColor Yellow

# Check if we're in Laravel project directory
if (-not (Test-Path "artisan")) {
    Write-Host "Error: artisan file not found. Make sure you're in the Laravel project root directory." -ForegroundColor Red
    exit 1
}

# Clean cache if requested
if ($CleanCache) {
    Write-Host "Cleaning Laravel caches..." -ForegroundColor Blue
    
    if (Get-Command php -ErrorAction SilentlyContinue) {
        php artisan config:clear
        php artisan cache:clear
        php artisan view:clear
        php artisan route:clear
        Write-Host "✓ Laravel caches cleared" -ForegroundColor Green
    } else {
        Write-Host "Warning: PHP not found in PATH. Please clear caches manually." -ForegroundColor Yellow
    }
}

# Create deployment-ready environment file
Write-Host "Creating production environment file..." -ForegroundColor Blue

if (Test-Path ".env.production") {
    Copy-Item ".env.production" ".env.deployment"
    Write-Host "✓ .env.deployment created from .env.production" -ForegroundColor Green
} else {
    Write-Host "Warning: .env.production not found. Please create it manually." -ForegroundColor Yellow
}

# Create zip file for deployment if requested
if ($CreateZip) {
    Write-Host "Creating deployment zip file..." -ForegroundColor Blue
    
    # Files and folders to exclude
    $excludePatterns = @(
        "node_modules",
        "vendor",
        ".git",
        ".env",
        "storage\logs\*.log",
        "*.zip",
        "project_stunting*"
    )
    
    # Create temporary folder for deployment files
    $tempDir = "temp_deployment"
    if (Test-Path $tempDir) {
        Remove-Item $tempDir -Recurse -Force
    }
    New-Item -ItemType Directory -Path $tempDir | Out-Null
    
    # Copy files excluding unwanted items
    Write-Host "Copying files for deployment..." -ForegroundColor Blue
    
    Get-ChildItem -Path "." | Where-Object {
        $item = $_
        $exclude = $false
        foreach ($pattern in $excludePatterns) {
            if ($item.Name -like $pattern) {
                $exclude = $true
                break
            }
        }
        -not $exclude
    } | Copy-Item -Destination $tempDir -Recurse -Force
    
    # Create zip file
    if (Get-Command Compress-Archive -ErrorAction SilentlyContinue) {
        $zipPath = Join-Path (Get-Location) $ZipName
        if (Test-Path $zipPath) {
            Remove-Item $zipPath -Force
        }
        
        Compress-Archive -Path "$tempDir\*" -DestinationPath $zipPath -CompressionLevel Optimal
        Write-Host "✓ Deployment zip created: $ZipName" -ForegroundColor Green
        
        # Show zip file size
        $zipSize = [math]::Round((Get-Item $zipPath).Length / 1MB, 2)
        Write-Host "  File size: $zipSize MB" -ForegroundColor Yellow
    } else {
        Write-Host "Error: Compress-Archive not available. Please create zip manually." -ForegroundColor Red
    }
    
    # Clean up temporary directory
    Remove-Item $tempDir -Recurse -Force
}

Write-Host ""
Write-Host "=== Deployment Preparation Summary ===" -ForegroundColor Green
Write-Host "1. ✓ Checked Laravel project structure" -ForegroundColor White
Write-Host "2. $(if ($CleanCache) { '✓ Cleared Laravel caches' } else { '- Skipped cache cleaning (use -CleanCache)' })" -ForegroundColor White
Write-Host "3. $(if (Test-Path '.env.deployment') { '✓ Created .env.deployment file' } else { '⚠ Missing .env.production file' })" -ForegroundColor White
Write-Host "4. $(if ($CreateZip -and (Test-Path $ZipName)) { "✓ Created deployment zip: $ZipName" } else { '- Skipped zip creation (use -CreateZip)' })" -ForegroundColor White

Write-Host ""
Write-Host "Next Steps:" -ForegroundColor Cyan
Write-Host "1. Review and update .env.deployment with your hosting details" -ForegroundColor White
Write-Host "2. Upload the zip file to your cPanel hosting" -ForegroundColor White
Write-Host "3. Follow the HOSTING_GUIDE.md for detailed deployment steps" -ForegroundColor White
Write-Host "4. Use DEPLOYMENT_CHECKLIST.md to ensure all steps are completed" -ForegroundColor White

Write-Host ""
Write-Host "Usage Examples:" -ForegroundColor Cyan
Write-Host "  .\prepare-deployment.ps1 -CreateZip" -ForegroundColor Gray
Write-Host "  .\prepare-deployment.ps1 -CleanCache -CreateZip" -ForegroundColor Gray
Write-Host "  .\prepare-deployment.ps1 -CreateZip -ZipName 'myproject.zip'" -ForegroundColor Gray
