FROM registry.gitlab.com/c-more/base-images/php:production-latest

COPY . /var/www/html

WORKDIR /var/www/html

RUN mv docker/php-apache/php-apache-entrypoint.sh /php-apache-entrypoint.sh \
    && mv docker/php-apache/apache.conf /etc/apache2/sites-enabled/000-default.conf \
    && mv docker/php-apache/ports.conf /etc/apache2/ports.conf \
    && mv docker/php-worker/php-worker-entrypoint.sh /php-worker-entrypoint.sh \
    && mv docker/php-worker/php-worker-entrypoint.dev.sh /php-worker-entrypoint.dev.sh \
    && mv docker/php-worker/php-worker-custom.sh /php-worker-custom.sh \
    && mv docker/php-worker/php-worker-default.sh /php-worker-default.sh \
    && mv docker/php-worker/supervisord.d /etc/supervisord.d \
    && mv docker/php-worker/supervisord.conf /etc/supervisord.conf \
    && chmod +x /php-apache-entrypoint.sh /php-worker-entrypoint.sh \
    && chmod +x /php-apache-entrypoint.sh /php-worker-entrypoint.dev.sh \
    && chmod +x /php-worker-default.sh \
    && chmod +x /php-worker-custom.sh  \
    && composer install --no-dev \
    && npm i --save-exact  \
    && npm run prod \
    && rm auth.json

RUN chown -R www-data:www-data /var/www/

USER www-data

EXPOSE 8080
