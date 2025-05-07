FROM php:7.4-apache
COPY ./app /var/www/html/
RUN docker-php-ext-install mysqli
RUN apt-get update && apt-get install -y iputils-ping