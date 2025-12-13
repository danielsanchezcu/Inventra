FROM php:8.2-apache

# Instalar dependencias y extensiones
RUN apt-get update && apt-get install -y libzip-dev zip unzip git curl \
 && docker-php-ext-install mysqli pdo pdo_mysql zip \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilitar módulos de Apache necesarios
RUN a2enmod rewrite && a2enmod headers

# Evitar warning de ServerName (opcional)
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Copiar todo el proyecto al contenedor
COPY . /var/www/html

# Crear carpeta uploads y dar permisos
RUN mkdir -p /var/www/html/uploads && chown -R www-data:www-data /var/www/html/uploads

# 🔧 NUEVO: Crear/asegurar carpeta imagenes y dar permisos (para que Mpdf pueda acceder a los logos)
RUN mkdir -p /var/www/html/imagenes && chown -R www-data:www-data /var/www/html/imagenes && chmod -R 755 /var/www/html/imagenes

# 🔧 Habilitar soporte para tu build de React
# Esto permite que Apache sirva los archivos estáticos dentro de /consultar-inventario/
# y no devuelva error 404 en rutas internas
RUN echo "<Directory /var/www/html/consultar-inventario>\n\
    Options -Indexes +FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf

EXPOSE 80
CMD ["apache2-foreground"]