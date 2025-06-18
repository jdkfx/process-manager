FROM php:8.4-cli

ENV TZ=Asia/Tokyo

RUN docker-php-ext-install pcntl

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . /app

RUN chmod +x /app/bin/run.php
