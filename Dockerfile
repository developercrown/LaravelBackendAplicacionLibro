FROM php:7.2-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

RUN echo "file_uploads = On\n" \
         "memory_limit = 500M\n" \
         "upload_max_filesize = 500M\n" \
         "post_max_size = 500M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini

LABEL Name="php:7.2-custom"
LABEL Version="0.0.1"
LABEL maintainer="ingeniero.rene.corona@gmail.com"