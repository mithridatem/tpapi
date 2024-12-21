<?php

//chargement des variables d'environnement
require_once './env.local.php';

//chargement de l'autoloader de composer
require_once './vendor/autoload.php';

//utilisation de session_start(pour gérer la connexion au serveur)
session_start();

//Analyse de l'URL avec parse_url() et retourne ses composants
$url = parse_url($_SERVER['REQUEST_URI']);
//test si l'url posséde une route sinon on renvoi à la racine
$path = $url['path'] ??  '/';

//Récupération du token JWT
$bearer = isset($_SERVER['HTTP_AUTHORIZATION']) ? preg_replace(
    '/Bearer\s+/',
    '',
    $_SERVER['HTTP_AUTHORIZATION']
) : null;

//importer les classes du router
use App\Router\Router;
use App\Router\Route;

//Instance du Router
$router = new Router(substr($path, strlen(BASE_URL)), $bearer); 

//Ajout des routes
$router->addRoute(new Route('/', 'GET', 'Home', 'home'));
$router->addRoute(new Route('/user', 'GET', 'User', 'showAll'));
$router->addRoute(new Route('/user', 'POST', 'User', 'save'));
$router->addRoute(new Route('/user/id', 'GET', 'User', 'showUser'));
$router->addRoute(new Route('/user/token', 'GET', 'User', 'verifyUserToken', [$bearer]));
$router->addRoute(new Route('/user/token', 'POST', 'User', 'getUserToken'));
$router->addRoute(new Route('/user/me', 'GET', 'User', 'showMe', [$bearer]));
$router->addRoute(new Route('/user/me2', 'GET', 'User', 'showMeV2'));

//Lancement du Router
$router->run();
