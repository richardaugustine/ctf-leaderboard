FROM php:8.2-apache
RUN apt-get update && apt-get install -y sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && a2enmod rewrite
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html \
    && touch /var/www/html/ctf.db && chown www-data:www-data /var/www/html/ctf.db
EXPOSE 80
CMD ["apache2-foreground"]
