#!/bin/sh
# Container start: listen on the port the host gives us, rebuild the demo
# database, cache config, then run Apache in the foreground.
set -e
cd /var/www/html

PORT="${PORT:-10000}"
sed -i "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Free hosting has no persistent disk: every start begins from the demo data.
if [ -z "$APP_KEY" ]; then
    export APP_KEY="base64:$(head -c 32 /dev/urandom | base64)"
fi
touch database/database.sqlite
php artisan migrate:fresh --seed --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

chown -R www-data:www-data storage bootstrap/cache database public/uploads
exec apache2-foreground
