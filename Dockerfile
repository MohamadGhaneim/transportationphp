FROM php:8.1-cli

# تثبيت المتطلبات اللازمة لـ PHP
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libonig-dev libpq-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql zip

# تعيين مجلد العمل
WORKDIR /var/www/html

# نسخ كل ملفات المشروع (بما فيها vendor)
COPY . .

# فتح المنفذ الذي تطلبه Render
EXPOSE 10000

# تشغيل الخادم المحلي داخل مجلد pages
CMD ["php", "-S", "0.0.0.0:10000", "-t", "pages"]
