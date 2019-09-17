FROM togetheragency/7.2-apache

ARG BUILD_ID
ENV BUILD_ID=$BUILD_ID

# PHP mail, Apache mod_remoteip + mod_headers
#COPY build/ssmtp.conf /etc/ssmtp/ssmtp.conf
#COPY build/revaliases /etc/ssmtp/revaliases
COPY build/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN chfn -f "Together" www-data && \
    a2enmod -f expires

# Generate vendor folder
COPY composer.* /var/www/
RUN cd /var/www/ && \
    COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --prefer-dist --optimize-autoloader --classmap-authoritative --no-scripts

# Copy in webroot
ADD . /var/www
RUN cd /var/www/ && \
    COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --prefer-dist --optimize-autoloader --classmap-authoritative --no-scripts

WORKDIR "/var/www"

#HEALTHCHECK CMD curl --fail http://localhost || exit 1