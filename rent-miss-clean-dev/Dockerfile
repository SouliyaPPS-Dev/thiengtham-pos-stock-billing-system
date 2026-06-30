FROM php:8.2-apache-bookworm

RUN docker-php-ext-install mysqli pdo_mysql

RUN a2enmod rewrite

# Configure Apache for Hugging Face Spaces port 7860
RUN sed -i 's/80/7860/g' /etc/apache2/ports.conf \
    && sed -i 's/:80/:7860/g' /etc/apache2/sites-available/000-default.conf

# Allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

WORKDIR /var/www/html

COPY . /var/www/html/

# Configure .env for HF Spaces — use external InfinityFree DB
RUN sed -i \
    -e 's/^APP_DEBUG=.*/APP_DEBUG=false/' \
    -e 's/^APP_ENV=.*/APP_ENV=production/' \
    -e 's|^APP_URL=.*|APP_URL=|' \
    -e 's|^PROD_APP_URL=.*|PROD_APP_URL=https://rent-miss-clean-pos.hf.space|' \
    -e 's|^PROD_DB_HOSTNAME=.*|PROD_DB_HOSTNAME=sql100.infinityfree.com|' \
    -e 's|^PROD_DB_USERNAME=.*|PROD_DB_USERNAME=if0_41710498|' \
    -e 's|^PROD_DB_PASSWORD=.*|PROD_DB_PASSWORD=6sp9EHHegsaQf|' \
    -e 's|^PROD_DB_DATABASE=.*|PROD_DB_DATABASE=if0_41710498_rent|' \
    .env

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 7860
ENTRYPOINT ["docker-entrypoint.sh"]
