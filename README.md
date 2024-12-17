# tpapi
Pour utiliser ce projet vous devez 

- 1 créer un fichier **env.local.php** à la racine

Il va contenir :

```php
<?php

//Paramètrage de la BDD
const DB_HOST = "url du serveur de BDD";
const DB_NAME = "nom de la BDD";
const DB_USER = "login de la BDD";
const DB_PASSWORD = "password de la BDD";
//base URL
const BASE_URL = '/tpapi/';

```

- 2 Générer la BDD avec le script **bdd.sql**

- 3 installer le projet :

Saisir la commande ci-dessous dans la console :

```bash
composer install
```

- 4 Générer vos clés de chiffrement **RSA**

Saisir les commandes ci-dessous dans la console :

```bash
openssl genrsa -out private.pem 2048
openssl rsa -in private.pem -pubout -out public.pem
```

- 5 Modifier le fichier **env.local.php**

Ajouter les lignes ci-dessous :

```php
//Temps de validitée du token JWT en minutes
const TOKEN_VALIDITY = 60;
//Clé de chiffrement du token JWT
const TOKEN_KEY = "coller-ici le contenu de votre clé publique"
```

- 6 Tester les EndPoints API avec **Bruno** :

Importer le dossier api dans Bruno (racine du projet -> importer une collection)
