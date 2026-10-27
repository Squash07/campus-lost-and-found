# Campus Lost & Found: production image for Render (or any Docker host).
# Apache + PHP 8.3 serving public/, SQLite demo database rebuilt on every start.
FROM php:8.3-apache

# The official image already includes pdo_sqlite, mbstring, openssl, fileinfo and curl.
RUN a2enmod rewrite \
 && sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
 && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
 && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
 && printf 'upload_max_filesize=4M\npost_max_size=8M\nexpose_php=Off\n' > "$PHP_INI_DIR/conf.d/app.ini"

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP packages first so this layer is cached between deploys
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --prefer-dist

COPY . .
# Make every file readable by Apache whatever permissions it had on the build machine
RUN composer dump-autoload --no-dev --optimize \
 && chmod -R a+rX /var/www/html \
 && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache public/uploads \
 && chown -R www-data:www-data storage bootstrap/cache database public/uploads

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

CMD ["entrypoint.sh"]
