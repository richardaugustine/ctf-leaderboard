FROM php:8.2-apache

# Install dependencies + extensions
RUN apt-get update && apt-get install -y \
    sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Apache config
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && a2enmod rewrite \
    && echo "<Directory /var/www/html>\n\
        RewriteEngine On\n\
        RewriteCond %{REQUEST_FILENAME} !-f\n\
        RewriteCond %{REQUEST_FILENAME} !-d\n\
        RewriteRule ^ index.php [L]\n\
        </Directory>" > /etc/apache2/conf-available/spa.conf \
    && a2enmod rewrite \
    && a2enconf spa

WORKDIR /var/www/html
COPY . .
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && touch ctf.db && chown www-data:www-data ctf.db

EXPOSE 80
CMD ["apache2-foreground"]
