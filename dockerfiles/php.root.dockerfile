# FROM php:8-fpm-alpine

# RUN mkdir -p /var/www/html

# WORKDIR /var/www/html

# COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# RUN sed -i "s/user = www-data/user = root/g" /usr/local/etc/php-fpm.d/www.conf
# RUN sed -i "s/group = www-data/group = root/g" /usr/local/etc/php-fpm.d/www.conf
# RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

# RUN docker-php-ext-install pdo pdo_mysql

# RUN mkdir -p /usr/src/php/ext/redis \
#     && curl -L https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
#     && echo 'redis' >> /usr/src/php-available-exts \
#     && docker-php-ext-install redis
    
# USER root

# CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]

# FROM php:8.2-fpm-alpine

# # Instalar dependencias del sistema
# RUN apk add --no-cache \
#     bash \
#     shadow \
#     curl \
#     git \
#     unzip \
#     libpng-dev \
#     libjpeg-turbo-dev \
#     freetype-dev \
#     oniguruma-dev \
#     icu-dev \
#     libzip-dev \
#     zip \
#     supervisor

# # Instalar extensiones de PHP
# RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
#     && docker-php-ext-install -j$(nproc) gd mbstring pdo pdo_mysql zip intl opcache bcmath \
#     && pecl install redis \
#     && docker-php-ext-enable redis

# # Configurar usuario y permisos
# ARG UID=1000
# ARG GID=1000
# RUN addgroup -g ${GID} appgroup && adduser -G appgroup -u ${UID} -D appuser
# RUN chown -R appuser:appgroup /var/www/html

# # Configurar directorio de trabajo
# WORKDIR /var/www/html

# # Configurar CMD por defecto
# CMD ["php-fpm"]

FROM php:8.2-fpm-alpine

# Actualiza los paquetes y agrega las dependencias necesarias
RUN apk update && apk add --no-cache \
    bash \
    shadow \
    supervisor \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    sqlite-dev \
    zip \
    unzip \
    curl \
    nano \
    autoconf \
    gcc \
    g++ \
    make \
    libc-dev \
    pkg-config \
    tar

# Configuración de usuario y permisos
RUN usermod -u 1000 www-data

# Instala las extensiones de PHP necesarias
RUN docker-php-ext-install zip pdo pdo_pgsql pdo_sqlite mbstring \
    && pecl install redis \
    && docker-php-ext-enable redis

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuración del entorno
WORKDIR /var/www/html

# Copia los archivos del proyecto
COPY . .

# Permisos y configuración de Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data
