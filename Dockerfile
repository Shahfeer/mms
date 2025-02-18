# Use the official PHP-Apache base image
FROM php:8.1-apache

# Install required PHP extensions and other dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli

# Enable Apache modules
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Copy the application files to the container
COPY . /var/www/html/

# Copy custom Apache configuration
COPY mms-apache.conf /etc/apache2/sites-available/000-default.conf

# Set proper permissions for Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Install Node.js and Angular CLI if needed
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g @angular/cli

# Install Node.js dependencies if package.json exists
RUN if [ -f package.json ]; then npm install; fi

# Expose the Apache port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]                           
