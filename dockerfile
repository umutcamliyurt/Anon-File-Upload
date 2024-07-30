# Use the official PHP image with Apache
FROM php:8.3-apache

# Necessary to set headers using php such as the CSP header
RUN a2enmod headers

# Copy your PHP files to the container
COPY . /var/www/html/

# Change working directory to the document root
WORKDIR /var/www/html

# Set the ownership of the files to www-data user and group
RUN chown -R www-data:www-data /var/www/html

# Set the permissions of the uploads directory
RUN chmod 750 uploads

# Expose port 80 to the host
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
