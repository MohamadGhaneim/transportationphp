FROM php:8.1-cli

# تثبيت الأدوات اللازمة + mysqli
RUN apt-get update && apt-get install -y \
    git unzip curl zip libzip-dev libonig-dev libpq-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql

# تثبيت Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# تحديد مجلد العمل داخل الحاوية
WORKDIR /var/www/html

# نسخ الملفات إلى داخل الحاوية
COPY . .

# تثبيت مكتبات PHP من composer.json
RUN composer install --no-dev --optimize-autoloader

# فتح المنفذ المطلوب لتشغيل السيرفر
EXPOSE 10000

# تشغيل السيرفر المحلي على مجلد pages
CMD ["php", "-S", "0.0.0.0:10000", "-t", "pages"]
