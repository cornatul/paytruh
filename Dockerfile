# Use the official PHP 8.1 image as the base image
FROM php:8.1-apache

# Install necessary extensions and libraries
RUN apt-get update && \
    apt-get install -y \
        libicu-dev \
        libsodium-dev \
        libonig-dev \
        zlib1g-dev \
        libzip-dev \
    && docker-php-ext-install pdo_mysql intl mbstring zip sodium
# Set the working directory to the web root
WORKDIR /var/www/html

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache modules
RUN a2enmod rewrite

# Copy the CodeIgniter project files to the container
COPY . .

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Set the correct permissions for the CodeIgniter application

RUN chmod 775 writable/
RUN chmod 775 writable/cache/
RUN chmod 775 writable/logs/
RUN chmod 775 writable/session/

# Copy Apache vhost file to proxy php requests to php-fpm container
COPY ./docker/apache-config.conf /etc/apache2/sites-available/000-default.conf

# Expose port 80 for web traffic
EXPOSE 80

# Start Apache web server
CMD ["apache2-foreground"]