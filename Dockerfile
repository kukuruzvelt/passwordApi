# Use an official PHP image as the base image
FROM php:8.2.7-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev

# Install PHP extensions
RUN docker-php-ext-configure zip && docker-php-ext-install zip pdo pdo_mysql gd

# Set the working directory in the container
WORKDIR /srv/app

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy your Symfony project into the container
COPY . .

# Install project dependencies using Composer
RUN composer install

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]