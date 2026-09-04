$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot
$cmd = Get-Command php -ErrorAction SilentlyContinue
if ($cmd) { $php = $cmd.Source } else {
    $php = Get-ChildItem 'C:\MAMP\bin\php' -Directory -ErrorAction SilentlyContinue |
        Sort-Object Name -Descending |
        ForEach-Object { Join-Path $_.FullName 'php.exe' } |
        Where-Object { Test-Path $_ } |
        Select-Object -First 1
}
if (-not $php) { throw 'PHP not found. Start MAMP PRO or add PHP to PATH.' }
& $php artisan site:factory:verify-db
if ($LASTEXITCODE -ne 0) { throw 'FINAL database integrity check failed. Run INSTALL-WINDOWS.ps1 first.' }
& $php artisan serve --host=127.0.0.1 --port=8000 --no-reload
