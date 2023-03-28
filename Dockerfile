FROM richarvey/nginx-php-fpm

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Удаляем папку, которая мешает
RUN rm -rf /var/www/html

# Копируем проект в контейнер
COPY . /var/www

# Устанавливаем зависимости проекта
RUN cd /var/www && composer install --no-interaction

# Устанавливаем права доступа
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www