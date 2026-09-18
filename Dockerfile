FROM php:8.2-cli

# Menginstal ekstensi sistem untuk MySQL dan Zip
RUN apt-get update -y && apt-get install -y libzip-dev zip unzip
RUN docker-php-ext-install pdo_mysql zip

# Mengambil Composer untuk menginstal vendor Laravel
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Menentukan lokasi folder di dalam server Render
WORKDIR /app

# Memasukkan semua file proyek Anda ke server
COPY . .

# Menjalankan instalasi aplikasi
RUN composer install --optimize-autoloader --no-dev

# Perintah untuk menyalakan web secara otomatis
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}