# tpapi
Pour utiliser ce projet vous devez :

**Prérequis** :

```txt
-php 8.2 +
-MYSql MariaDB,
-apache 2 +
-composer 2.7 +
```

- 1 cloner le repository dans votre racine apache

```bash
git clone https://github.com/mithridatem/tpapi.git
```
- 2 créer un fichier **env.local.php** à la racine

Il va contenir :

```php
<?php

//Paramètrage de la BDD
const DB_HOST = "url du serveur de BDD";
const DB_NAME = "nom de la BDD";
const DB_USER = "login de la BDD";
const DB_PASSWORD = "password de la BDD";
//base URL (/nom_dossier dans apache)
const BASE_URL = '/tpapi';

```

- 3 Générer la BDD avec le script **bdd.sql**

- 4 installer le projet :

Saisir la commande ci-dessous dans la console :

```bash
composer install
```

- 5 Générer vos clés de chiffrement **RSA**

Saisir les commandes ci-dessous dans la console :

```bash
openssl genrsa -out private.pem 2048
openssl rsa -in private.pem -pubout -out public.pem
```

- 6 Modifier le fichier **env.local.php**

Ajouter les lignes ci-dessous :

```php
//Temps de validitée du token JWT en minutes
const TOKEN_VALIDITY = 60;
//Clé de chiffrement du token JWT
const TOKEN_SECRET_KEY = "coller-ici le contenu de votre clé publique"
```

- 7 Tester les EndPoints API avec **Bruno** :

Importer le dossier api dans Bruno (racine du projet -> importer une collection)

**Nouveautés** :

Mise en place d'un nouveau système de routage avec les classes Router et Route 
afin de simplifier la création de Nouveau EndPoint.

*Pour ajouter une nouvelle route au projet* :

```php
//Exemple d'ajout d'une nouvelle route sans paramètres
/*
On passe les paramètres suivants 
l'url de la route(découpée par la taille de la constante BASE_URL dans env.local.php),
la méthode HTTP, le nom du controller (sans Controller à la fin), 
le nom de la fonction qui se trouve dans le controller,
un tableau qui contient les paramètres de la méthode ou rien si on n'a pas de paramètes
*/

$router->addRoute(new Route('/test', 'GET', 'Test', 'fonction'));

//Exemple d'ajout d'une nouvelle route avec des paramètres (tableau de paramètres en dernier paramètre)

$router->addRoute(new Route('/test', 'GET', 'Test', 'fonction', ['valeur1', ...]));

```
**NB** : Ajouter les routes avant le lancement du routeur ($router->run()).

- 8 Ajouter vos routes dans index.php (voir index.php du repository)
