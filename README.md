# Php-fpm-alpine x Nginx
### Symfony | Docker

## PREMIERE INSTALLATION DU PROJET - COMMANDES BASH SYMFONY :

Se rendre dans le container Symfony :
````shell
docker exec -ti {id_container} bash
````

Après être allé dans le dossier /html et avoir fait le composer install, créer la db :
````shell
symfony console d:d:c
````

Effectuer la migration déjà préparée :
````shell
symfony console d:m:m
````

Créer la data fictive depuis les fixtures :
````shell
symfony console d:f:l
````