FROM php:8.1-apache

# Copy all project files into the container
COPY . /var/www/html/

# Enable Apache mod_rewrite (needed for some PHP apps)
RUN a2enmod rewrite

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
