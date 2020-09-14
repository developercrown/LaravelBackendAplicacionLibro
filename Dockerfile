FROM php:7.2-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

LABEL Name="php:7.2-custom"
LABEL Version="0.0.1"
LABEL maintainer="ingeniero.rene.corona@gmail.com"