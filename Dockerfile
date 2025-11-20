# Usar imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install curl \
    && apt-get clean

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . /var/www/html/

# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exponer puerto (Railway asigna automáticamente)
EXPOSE 80

# Script de inicio que configura el puerto dinámicamente
COPY <<EOF /usr/local/bin/start-apache.sh
#!/bin/bash
sed -i "s/Listen 80/Listen \${PORT:-80}/g" /etc/apache2/ports.conf
sed -i "s/:80>/:"\${PORT:-80}">/g" /etc/apache2/sites-available/000-default.conf
apache2-foreground
EOF

RUN chmod +x /usr/local/bin/start-apache.sh

# Iniciar Apache con configuración dinámica
CMD ["/usr/local/bin/start-apache.sh"]
