FROM php:8.2-cli-alpine

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /usr/src/app
WORKDIR /usr/src/app

RUN composer install

EXPOSE 8080

CMD ["-S", "0.0.0.0:8080", "src/server.php"]
