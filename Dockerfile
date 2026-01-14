FROM php:8.2-apache

# Install SQLite3 + PHP extensions (what you NEED)
RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy all files
COPY . /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && touch /var/www/html/ctf.db \
    && chown www-data:www-data /var/www/html/ctf.db

EXPOSE 8080
CMD ["apache2-foreground"]
