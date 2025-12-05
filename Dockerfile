FROM php:8.3-apache

#PHP + extensions
RUN apt-get update \
    && apt-get install -y git unzip \
    && docker-php-ext-install pdo pdo_mysql

#Récuperation de Composer
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

#Soucis avec apache : rewrite + vhost Symfony
RUN a2enmod rewrite
COPY vhost.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default.conf

#Repertoire de travail
WORKDIR /var/www/app

#Au demarrage du docker installation des composants Symfony dont il 'ya besoin (form, validator, twig, orm-pack)
# Puis lancement d’Apache
CMD ["bash", "-c", "\
    composer install --no-interaction --no-progress || true && \
    composer require symfony/form symfony/validator symfony/twig-bundle symfony/orm-pack --no-interaction --no-progress --no-scripts || true && \
    apache2-foreground \
"]
