# Sử dụng image PHP 8.3 với Apache (giống WAMP local)
FROM php:8.3-apache

# Cài dependencies hệ thống cho Postgres (libpq-dev cho headers như libpq-fe.h)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*  # Clean để image nhẹ hơn

# Cài PHP extensions: pdo và pgsql (bây giờ sẽ compile OK với libpq-dev)
RUN docker-php-ext-install pdo pgsql

# Copy tất cả file project vào Apache's document root
COPY . /var/www/html/

# Set quyền cho Apache (giống WAMP)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80 cho HTTP
EXPOSE 80

# Chạy Apache foreground (start server như Apache trong WAMP)
CMD ["apache2-foreground"]