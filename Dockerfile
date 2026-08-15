FROM php:8.4-apache

# Installer les dépendances systèmes nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libsqlite3-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Activer le module de réécriture d'Apache (pour les jolies URLs de Laravel)
RUN a2enmod rewrite

# Copier Composer depuis l'image officielle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers du projet (tout le reste)
COPY . /var/www/html

# Installer les dépendances PHP via Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Adapter le fichier .env copié pour Render
RUN cp .env.example .env \
    && sed -i 's/SESSION_DRIVER=database/SESSION_DRIVER=file/' .env \
    && touch database/database.sqlite \
    && php artisan migrate --force

# Ajuster les permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configurer Apache pour pointer vers le dossier "public" de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Exposer le port (Render écoute souvent sur 80 pour les services Web)
EXPOSE 80

# Commande de démarrage
CMD ["apache2-foreground"]
