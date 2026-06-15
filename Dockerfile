FROM php:8.3.30-cli
RUN docker-php-ext-install pdo pdo_sqlite
WORKDIR /app
COPY db.php /app
CMD ["php", "/app/db.php"]
# EXPOSE