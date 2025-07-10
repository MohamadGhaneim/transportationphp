FROM php:8.1-cli

RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libonig-dev libpq-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql

# تعيين مجلد العمل
WORKDIR /var/www/html

# نسخ ملفات المشروع (بدون .env)
COPY . .

# في حال كان vendor موجود محليًا، لن تحتاج composer install
RUN if [ ! -d "vendor" ]; then \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    composer install --no-dev --optimize-autoloader; \
    fi

EXPOSE 10000

CMD ["php", "-S", "0.0.0.0:10000", "-t", "pages"]
