# Sử dụng image PHP 8.3 với Apache (giống WAMP)
FROM php:8.3-apache

# Cài extension PDO cho Postgres (thay vì MySQL)
RUN docker-php-ext-install pdo pgsql

# Copy tất cả file project vào container (www folder của Apache)
COPY . /var/www/html/

# Set quyền cho Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80 (HTTP)
EXPOSE 80

# Chạy Apache foreground (start server)
CMD ["apache2-foreground"]