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

function Read-EnvValue([string]$Key) {
    $line = Get-Content '.env' | Where-Object { $_ -match "^$([regex]::Escape($Key))=" } | Select-Object -First 1
    if (-not $line) { return '' }
    $value = ($line -split '=', 2)[1].Trim()
    return $value.Trim('"').Trim("'")
}

function Assert-LastExit([string]$Step) {
    if ($LASTEXITCODE -ne 0) { throw "$Step failed with exit code $LASTEXITCODE." }
}

$php = Find-PHP
Write-Host "Using PHP: $php"

if (-not (Test-Path '.env')) {
    Copy-Item '.env.mamp.example' '.env'
    Write-Host 'Created .env from .env.mamp.example'
}

$dbName = Read-EnvValue 'DB_DATABASE'
$dbHost = Read-EnvValue 'DB_HOST'
$dbPort = Read-EnvValue 'DB_PORT'
$dbUser = Read-EnvValue 'DB_USERNAME'
$dbPass = Read-EnvValue 'DB_PASSWORD'

# Create the isolated FINAL local database automatically when the MAMP MySQL client is available.
$mysql = Get-ChildItem 'C:\MAMP\bin\mysql' -Filter 'mysql.exe' -Recurse -ErrorAction SilentlyContinue |
    Select-Object -First 1 -ExpandProperty FullName
if ($mysql -and $dbName) {
    $args = @('-h', $dbHost, '-P', $dbPort, '-u', $dbUser)
    if ($dbPass) { $args += "-p$dbPass" }
    $tick = [char]96
    $sql = "CREATE DATABASE IF NOT EXISTS $tick$dbName$tick CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    & $mysql @args '-e' $sql
    Assert-LastExit 'Database creation'
    Write-Host "Database ready: $dbName"
} elseif (-not $mysql) {
    Write-Warning 'MAMP mysql.exe was not found automatically. Create the DB_DATABASE value from .env in phpMyAdmin, then rerun this installer.'
}

if (-not (Test-Path 'vendor\autoload.php')) {
    $composer = Get-Command composer -ErrorAction SilentlyContinue
    if (-not $composer) { throw 'vendor is missing and Composer was not found.' }
    & composer install --no-interaction
    Assert-LastExit 'Composer install'
}

if (-not (Read-EnvValue 'APP_KEY')) {
    & $php artisan key:generate --force
    Assert-LastExit 'Laravel key generation'
}

& $php artisan site:factory:validate --content=content/en --strict
Assert-LastExit 'Content validation'

& $php artisan site:factory:audit
Assert-LastExit 'Presentation readiness audit'

& $php artisan site:factory:rebuild --content=content/en --yes
Assert-LastExit 'Database rebuild'

& $php artisan site:factory:verify-db
Assert-LastExit 'Database integrity audit'

$npm = Get-Command npm -ErrorAction SilentlyContinue
$prebuiltManifest = Test-Path 'public\build\manifest.json'
if (Test-Path 'node_modules') {
    if ($npm) {
        & npm run build
        if ($LASTEXITCODE -ne 0) {
            if ($prebuiltManifest) {
                Write-Warning 'Frontend rebuild failed; using the packaged public/build assets instead.'
            } else {
                throw "Frontend build failed with exit code $LASTEXITCODE and no packaged manifest is available."
            }
        }
    } elseif (-not $prebuiltManifest) {
        throw 'npm is unavailable and no prebuilt public/build/manifest.json exists.'
    }
} elseif ($prebuiltManifest) {
    Write-Host 'Using packaged frontend build assets; npm install is not required for this FULL package.'
} elseif ($npm) {
    & npm ci
    Assert-LastExit 'npm install'
    & npm run build
    Assert-LastExit 'Frontend build'
} else {
    throw 'npm is unavailable and no prebuilt public/build/manifest.json exists.'
}

& $php artisan optimize:clear
Assert-LastExit 'Laravel cache clear'
Write-Host ''
Write-Host 'Turkelite Medcare FINAL-MGMT is ready.'
Write-Host 'Run: .\START-WINDOWS.ps1'
Write-Host 'Then open: http://127.0.0.1:8135'
