FROM php:8.1-cli

# مجلد العمل داخل الحاوية
WORKDIR /var/www/html

# نسخ جميع الملفات (بما فيها pages و phpmailer)
COPY . .

# فتح المنفذ المطلوب من Render
EXPOSE 10000

# تشغيل السيرفر المحلي داخل مجلد pages
CMD ["php", "-S", "0.0.0.0:10000", "-t", "pages"]
