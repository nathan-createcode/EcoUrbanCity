# Menggunakan image PHP dengan Apache
FROM php:7.4-apache

# Instal ekstensi mysqli
RUN docker-php-ext-install mysqli

# Menyalin file aplikasi ke dalam direktori Apache
COPY ./app/ /var/www/html/

# Aktifkan modul rewrite Apache (jika diperlukan)
RUN a2enmod rewrite

# Mengekspos port 80 agar bisa diakses dari luar container
EXPOSE 80
