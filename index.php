<?php
//chargement de l'autoloader de composer
require_once './vendor/autoload.php';
//chargement des variables d'environnement
//require_once './env.local.php';

//utilisation de session_start(pour gérer la connexion au serveur)
session_start();
//Analyse de l'URL avec parse_url() et retourne ses composants
$url = parse_url($_SERVER['REQUEST_URI']);
//test si l'url posséde une route sinon on renvoi à la racine
$path = $url['path'] ??  '/';

//importer le repository
//importer le UserController
use App\Controller\UserController;

$userController = new UserController();

//routeur
switch ($path) {
    case '/tpapi/':
        echo 'Bienvenue';
        break;
    case '/tpapi/user':
        $userController->save();
        break;
    default:
        echo "erreur 404";
        break;
}
