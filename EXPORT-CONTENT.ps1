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

& $php artisan site:factory:export --content=content/en
if ($LASTEXITCODE -ne 0) { throw 'Content export failed.' }

& $php artisan site:factory:validate --content=content/en --strict
if ($LASTEXITCODE -ne 0) { throw 'Export completed but validation found an issue.' }

Write-Host 'CMS edits exported back to content/en and validated.'
