FROM php:8.2-apache

# Install PostgreSQL extension for Supabase
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Enable Apache mod_rewrite (if needed)
RUN a2enmod rewrite

# Copy all files (your original + our leaderboard)
COPY . /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 8080
CMD ["apache2-foreground"]
