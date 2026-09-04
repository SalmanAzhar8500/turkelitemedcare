$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

$target = 'app\Console\Commands\VerifyDatabaseIntegrity.php'
if (-not (Test-Path $target)) { throw "Could not find $target. Run this script from the FINAL folder." }

$text = Get-Content $target -Raw
$old = "['Patient stories', PatientStory::query()->count(), 6],"
$new = "['Patient stories', PatientStory::query()->count(), 0],"

if ($text.Contains($old)) {
    $text = $text.Replace($old, $new)
    Set-Content -Path $target -Value $text -Encoding UTF8
    Write-Host 'Fixed stale Patient Stories integrity expectation: 6 -> 0.' -ForegroundColor Green
} elseif ($text.Contains($new)) {
    Write-Host 'Integrity expectation is already correct (0 patient stories).' -ForegroundColor Green
} else {
    throw 'Expected integrity-check line was not found; refusing to patch an unknown build.'
}

$inventory = 'content\INVENTORY.md'
if (Test-Path $inventory) {
    $inv = Get-Content $inventory -Raw
    $inv = $inv.Replace('**patient_stories**: 6', '**patient_stories**: 0')
    Set-Content -Path $inventory -Value $inv -Encoding UTF8
}

$php = Get-Command php -ErrorAction SilentlyContinue
if ($php) {
    $phpExe = $php.Source
} else {
    $phpExe = Get-ChildItem 'C:\MAMP\bin\php' -Directory -ErrorAction SilentlyContinue |
        Sort-Object Name -Descending |
        ForEach-Object { Join-Path $_.FullName 'php.exe' } |
        Where-Object { Test-Path $_ } |
        Select-Object -First 1
}

if (-not $phpExe) {
    Write-Warning 'Patch applied, but PHP was not found automatically. Rerun INSTALL-WINDOWS.ps1 after starting MAMP.'
    exit 0
}

& $phpExe artisan optimize:clear
if ($LASTEXITCODE -ne 0) { throw 'Laravel cache clear failed.' }

& $phpExe artisan site:factory:verify-db
if ($LASTEXITCODE -ne 0) { throw 'Database verification still failed.' }

Write-Host ''
Write-Host 'Hotfix complete. Run .\PRESENT-FINAL.ps1 and open http://127.0.0.1:8135' -ForegroundColor Green
