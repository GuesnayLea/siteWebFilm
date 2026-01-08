# Site Web pour Films
Projet web pour la gestion de films


Exécutez les commandes suivantes:
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"

Dans le dossier calculatrice faites : 
php ../composer.phar create-project symfony/skeleton .
php ../composer.phar require twig symfony/asset

Et enfin dans le dossier calculatrice : 
php -S 0.0.0.0:8082 -t public


Installer Validator :
php ../composer.phar require symfony/validator


 sudo docker-compose up -d 

phpMyAdmin : http://localhost:8080

Symfony : http://localhost:8084

dans téléchargement avec le fichier php8.tar
 sudo docker run --rm -it --name php7Dev -p 8888:80 -p 3306:3306 -p 8889:8085 -v "$HOME/Mes_projets_web:/home/php_dev/Mes_projets_web" php8-dev


# Lancez les conteneurs Docker
sudo docker-compose up -d

# Accédez au conteneur app
sudo docker exec -it symfony-app2 bash

# À l'intérieur du conteneur, créez le projet Symfony
composer create-project symfony/skeleton:"6.4.*" .

# Installez les dépendances nécessaires
composer require webapp

# Ajoutez les dépendances spécifiques
composer require symfony/orm-pack
composer require symfony/form
composer require symfony/validator
composer require symfony/security-bundle
composer require symfony/twig-pack

# Créez les entités Doctrine
php bin/console doctrine:mapping:import "App\Entity" annotation --path=src/Entity --force