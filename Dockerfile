FROM php:8.4-cli
RUN docker-php-ext-install mysqli
COPY . /app
WORKDIR /app
CMD php -S 0.0.0.0:$PORT index.php