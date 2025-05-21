FROM php:8.4-cli

ENV TZ=Asia/Tokyo

RUN docker-php-ext-install pcntl

WORKDIR /app

COPY . /app

RUN chmod +x /app/bin/run.php
