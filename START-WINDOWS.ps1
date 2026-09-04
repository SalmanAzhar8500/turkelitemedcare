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
$cmd = Get-Command php -ErrorAction SilentlyContinue
if ($cmd) { $php = $cmd.Source } else {
    $php = Get-ChildItem 'C:\MAMP\bin\php' -Directory -ErrorAction SilentlyContinue |
        Sort-Object Name -Descending |
        ForEach-Object { Join-Path $_.FullName 'php.exe' } |
        Where-Object { Test-Path $_ } |
        Select-Object -First 1
}
if (-not $php) { throw 'PHP not found. Start MAMP PRO first.' }
Write-Host 'Turkelite FINAL-MGMT LOCAL -> http://127.0.0.1:8000'
Write-Host 'Build check -> http://127.0.0.1:8000/__build'
Write-Host 'Checking FINAL database integrity before the server starts...'
& $php artisan site:factory:verify-db
if ($LASTEXITCODE -ne 0) { throw 'FINAL database integrity check failed. Run INSTALL-WINDOWS.ps1 before serving this build.' }
& $php artisan serve --host=127.0.0.1 --port=8000 --no-reload
