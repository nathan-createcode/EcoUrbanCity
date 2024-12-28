# Dockerfile
FROM php:apache

# Install ekstensi mysqli
RUN docker-php-ext-install mysqli

# Salin file aplikasi Anda ke dalam kontainer
COPY app/ /var/www/html/

# Set izin untuk direktori
RUN chown -R www-data:www-data /var/www/html