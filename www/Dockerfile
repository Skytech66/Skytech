FROM php:8.1-apache

# Copy all project files from the current directory (www) into the container's root web directory
COPY . /var/www/html/

# Enable Apache mod_rewrite (needed for some PHP apps)
RUN a2enmod rewrite

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 for web traffic
EXPOSE 80
