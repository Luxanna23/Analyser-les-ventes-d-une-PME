FROM php:8.3.30-cli
RUN apt-get update && apt-get install -y libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite
WORKDIR /app
COPY . /app
CMD ["php", "/app/main.php"]
# EXPOSE