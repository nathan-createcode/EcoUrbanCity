# Menggunakan image MySQL resmi
FROM mysql:latest

# Menentukan environment variables untuk MySQL
ENV MYSQL_ROOT_PASSWORD=rootpassword
ENV MYSQL_DATABASE=ecourbancity
ENV MYSQL_USER=user
ENV MYSQL_PASSWORD=userpassword

# Menyalin file SQL untuk menginisialisasi database (opsional)
COPY ./sql/ecourbancity.sql /docker-entrypoint-initdb.d/

# Mengekspos port 3306 agar dapat diakses dari luar container
EXPOSE 3306
