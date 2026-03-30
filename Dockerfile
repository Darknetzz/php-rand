# Official PHP 8.5 + Apache; GMP is built via docker-php-ext-install (same role as php8.5-gmp on Debian).
FROM php:8.5-apache

# php-rand version (set at build time via --build-arg PHP_RAND_VERSION=v1.2.5)
ARG PHP_RAND_VERSION=dev
ENV PHP_RAND_VERSION=${PHP_RAND_VERSION}

# Entrypoint: print version on start, then run the main process
RUN printf '%s\n' '#!/bin/sh' 'echo "php-rand ${PHP_RAND_VERSION:-unknown}"' 'exec "$@"' > /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

# Install system dependencies and common PHP extensions (adjust as needed)
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libzip-dev libicu-dev libonig-dev libpng-dev libjpeg-dev libfreetype6-dev libxml2-dev libgmp-dev git unzip \
        openssh-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mysqli intl zip gd gmp \
    && a2enmod rewrite headers expires \
    && rm -rf /var/lib/apt/lists/*

# Set recommended PHP settings (tune as necessary)
RUN { \
        echo "memory_limit=256M"; \
        echo "upload_max_filesize=32M"; \
        echo "post_max_size=32M"; \
        echo "date.timezone=UTC"; \
        echo "opcache.enable=1"; \
        echo "opcache.validate_timestamps=1"; \
        echo "opcache.max_accelerated_files=20000"; \
    } > /usr/local/etc/php/conf.d/custom.ini

RUN set -eux; \
    rm -rf /var/www/html/*; \
    git clone --depth=1 https://github.com/Darknetzz/php-rand.git /var/www/html; \
    chown -R www-data:www-data /var/www/html

# Document root (optional change)
# ENV APACHE_DOCUMENT_ROOT /var/www/html/public
# RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf

# Copy application (uncomment when you have source)
# COPY . /var/www/html

# Fix permissions (optional, adapt UID/GID for your environment)
# RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
HEALTHCHECK --interval=30s --timeout=3s CMD curl -f http://localhost/ || exit 1

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]