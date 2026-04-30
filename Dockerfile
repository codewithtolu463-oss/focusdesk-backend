FROM php:8.4-cli
RUN docker-php-ext-install mysqli
COPY . /app
WORKDIR /app
RUN chmod +x /app/start.sh
CMD ["/app/start.sh"]