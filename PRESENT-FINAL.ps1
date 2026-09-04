$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

# Laravel's file-based session/cache/view drivers require these runtime directories.
# Empty directories can disappear when ZIPs are extracted on Windows, so recreate them every time.
$runtimeDirs = @(
    'storage\framework\sessions',
    'storage\framework\views',
    'storage\framework\cache\data',
    'storage\logs',
    'bootstrap\cache'
)
foreach ($dir in $runtimeDirs) {
    if (-not (Test-Path $dir)) {
        New-Item -ItemType Directory -Path $dir -Force | Out-Null
    }
}
function Find-PHP {
    $cmd = Get-Command php -ErrorAction SilentlyContinue
    if ($cmd) { return $cmd.Source }
    $candidates = Get-ChildItem 'C:\MAMP\bin\php' -Directory -ErrorAction SilentlyContinue |
        Sort-Object Name -Descending |
        ForEach-Object { Join-Path $_.FullName 'php.exe' } |
        Where-Object { Test-Path $_ }
    if ($candidates) { return $candidates[0] }
    throw 'PHP was not found. Start MAMP PRO or add its PHP folder to PATH.'
}
function Assert-LastExit([string]$Step) { if ($LASTEXITCODE -ne 0) { throw "$Step failed with exit code $LASTEXITCODE." } }
$php = Find-PHP
Write-Host ''
Write-Host 'TURKELITE MEDCARE FINAL-MGMT - PRESENTATION BUILD'
Write-Host '------------------------------------------------'
Write-Host "Using PHP: $php"
if (-not (Test-Path '.env')) { Copy-Item '.env.mamp.example' '.env'; Write-Host 'Created .env from .env.mamp.example. Run INSTALL-WINDOWS.ps1 once before presenting.' }
& $php artisan site:factory:validate --content=content/en --strict
Assert-LastExit 'Content validation'
& $php artisan site:factory:audit
Assert-LastExit 'Presentation readiness audit'
& $php artisan site:factory:verify-db
Assert-LastExit 'Database integrity audit'
Write-Host ''
Write-Host 'PRESENTATION URLS - FINAL-MGMT HAS ITS OWN PORT'
Write-Host 'English: http://127.0.0.1:8000/'
Write-Host 'German:  http://127.0.0.1:8000/de'
Write-Host 'Arabic:  http://127.0.0.1:8000/ar'
Write-Host 'Admin:   http://127.0.0.1:8000/admin'
Write-Host ''
Write-Host 'The browser also shows a FINAL-MGMT LOCAL badge so the version cannot be confused.'
Write-Host 'Press Ctrl+C to stop the local presentation server.'
Write-Host ''
& $php artisan serve --host=127.0.0.1 --port=8000 --no-reload
