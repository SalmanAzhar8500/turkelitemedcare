#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"

if [ ! -f .env ]; then
  echo "ERROR: .env is missing. Copy .env.production.example to .env and fill production values." >&2
  exit 1
fi

if command -v composer >/dev/null 2>&1; then
  composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
elif [ ! -f vendor/autoload.php ]; then
  echo "ERROR: Composer is unavailable and vendor/ is missing." >&2
  exit 1
fi

# Generate an application key only when the deployment .env does not already have one.
if ! grep -Eq '^APP_KEY=.+$' .env; then
  php artisan key:generate --force
fi

php artisan site:factory:validate --content=content/en --strict

if command -v npm >/dev/null 2>&1; then
  npm ci
  npm run build
elif [ ! -d public/build ]; then
  echo "ERROR: npm is unavailable and public/build is missing." >&2
  exit 1
else
  echo "npm not found; using packaged public/build assets."
fi

# Production is deliberately non-destructive: migrations + upsert/prune content.
php artisan migrate --force
php artisan db:seed --force
php artisan site:factory:import --content=content/en --prune
php artisan sitemap:generate
php artisan optimize

echo "Deployment complete. Point the web server document root at: $(pwd)/public"
