$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

$phpCmd = Get-Command php -ErrorAction SilentlyContinue
if ($phpCmd) {
    $php = $phpCmd.Source
} else {
    $php = Get-ChildItem 'C:\MAMP\bin\php' -Directory -ErrorAction SilentlyContinue |
        Sort-Object Name -Descending |
        ForEach-Object { Join-Path $_.FullName 'php.exe' } |
        Where-Object { Test-Path $_ } |
        Select-Object -First 1
}
if (-not $php) { throw 'PHP was not found.' }

& $php artisan site:factory:validate --content=content/en --strict
if ($LASTEXITCODE -ne 0) { throw 'Validation failed. Database was not changed.' }

& $php artisan site:factory:import --content=content/en --prune
if ($LASTEXITCODE -ne 0) { throw 'Content import failed.' }

& $php artisan site:factory:verify-db
if ($LASTEXITCODE -ne 0) { throw 'Database integrity audit failed.' }

& $php artisan sitemap:generate
if ($LASTEXITCODE -ne 0) { throw 'Sitemap generation failed.' }

& $php artisan optimize:clear
Write-Host 'Content updated. No manual page-by-page population required.'
