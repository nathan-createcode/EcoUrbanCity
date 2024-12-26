FROM php:apache

# Install ekstensi mysqli
RUN docker-php-ext-install mysqli

# Salin file aplikasi Anda ke dalam kontainer
COPY . /var/www/html