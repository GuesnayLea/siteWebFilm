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

### Démarrer les conteneurs
docker-compose up -d

### Accéder au site
http://localhost:8084

### Accéder à phpMyAdmin
http://localhost:8080

### dans téléchargement avec le fichier php8.tar
 sudo docker run --rm -it --name php7Dev -p 8888:80 -p 3306:3306 -p 8889:8085 -v "$HOME/Mes_projets_web:/home/php_dev/Mes_projets_web" php8-dev

