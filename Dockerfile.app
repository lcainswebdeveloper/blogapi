FROM registry.gitlab.com/lcainswebdeveloper/docker-base-php72

USER root

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permiss
RUN chown www:www -R /var/www/html

# Change current user to www
USER www

RUN composer update --no-dev && composer dump-autoload -o

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
