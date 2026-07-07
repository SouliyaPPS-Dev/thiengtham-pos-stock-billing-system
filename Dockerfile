FROM php:8.2-apache

ARG DEBIAN_FRONTEND=noninteractive

# Install system dependencies and MySQL-compatible server (MariaDB)
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        mariadb-server \
        mariadb-client && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite headers

RUN docker-php-ext-install pdo pdo_mysql && \
    docker-php-ext-enable pdo_mysql

COPY . /var/www/html/

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
ENV APP_ENV=production
ENV APP_DEBUG=false

RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf && \
    sed -ri -e "s!/var/www/!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN sed -i 's/<Directory \/var\/www\/>/<Directory \/var\/www\/public>/' /etc/apache2/apache2.conf

RUN { \
    echo 'RewriteEngine On'; \
    echo 'RewriteRule ^/public/(.*)$ /$1 [PT]'; \
} >> /etc/apache2/apache2.conf

RUN mkdir -p /var/lib/php/sessions && \
    chmod -R 777 /var/lib/php/sessions

RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 777 /var/www/html/public/css

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# MySQL data directory - use persistent /data volume on HF Spaces
RUN mkdir -p /data/mysql /var/run/mysqld && \
    chown -R mysql:mysql /data/mysql /var/run/mysqld && \
    chmod 777 /var/run/mysqld

COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 7860

CMD ["/usr/local/bin/start.sh"]
