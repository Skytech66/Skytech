FROM php:8.1-apache

# Copy ONLY the contents of the www/ folder into Apache's web root
COPY www/ /var/www/html/

# Enable Apache mod_rewrite (needed for some PHP apps)
RUN a2enmod rewrite

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 for web traffic
EXPOSE 80
