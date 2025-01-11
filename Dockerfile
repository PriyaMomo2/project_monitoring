# Menggunakan image PHP dengan Nginx
FROM php:8.3-fpm

# Menginstal dependensi sistem dan ekstensi PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_pgsql

# Mengatur direktori kerja
WORKDIR /var/www

# Menyalin file composer dan menginstal dependensi
COPY composer.lock composer.json ./
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-autoloader --no-scripts

# Menyalin semua file aplikasi
COPY . .

# Menginstal dependensi dengan Composer
RUN composer dump-autoload

# Mengatur izin untuk folder storage dan bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Menjalankan server
CMD ["php-fpm"]
