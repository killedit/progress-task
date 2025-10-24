#!/bin/sh
set -e

cd /var/www/html

# Wait for the MySQL service to be ready
echo "Waiting for MySQL to be available..."
until php -r "try { new PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); } catch (Exception \$e) { exit(1); }"; do
  echo "MySQL not ready, retrying in 3 seconds..."
  sleep 3
done

echo "MySQL is ready. Running migrations and seeders..."

# Run migrations and seeders safely
php artisan migrate --force || true
php artisan db:seed --force || true

# Create the storage symlink if missing
if [ ! -L "public/storage" ]; then
    echo "Linking storage..."
    php artisan storage:link
fi

echo "Laravel setup complete. Starting php-fpm..."
exec php-fpm
