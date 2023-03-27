FROM php:8.0-fpm

# Устанавливаем дополнительные пакеты
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install zip pdo_mysql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Копируем проект в контейнер
COPY . /var/www

# Устанавливаем зависимости проекта
RUN cd /var/www && composer install --no-interaction

# Устанавливаем права доступа
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Запускаем PHP-FPM
CMD ["php-fpm"]