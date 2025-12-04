# Image PHP 8.3 với Apache (giống WAMP: PHP + Apache server)
FROM php:8.3-apache

# Cài libpq-dev cho Postgres headers (để compile pdo_pgsql)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*  # Clean cache, giữ image nhẹ

# Cài và enable PDO Postgres (đúng driver cho PDO::pgsql)
RUN docker-php-ext-install pdo_pgsql

# Copy code project vào Apache document root (/var/www/html/ - giống www trong WAMP)
COPY . /var/www/html/

# Set quyền file (Apache user: www-data, giống chmod trong WAMP)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80 (HTTP, như Apache listen 80 trong WAMP)
EXPOSE 80

# Start Apache foreground (chạy server như httpd.exe trong WAMP)
CMD ["apache2-foreground"]