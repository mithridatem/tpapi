<?php
//chargement de l'autoloader de composer
require_once './vendor/autoload.php';
//chargement des variables d'environnement
require_once './env.local.php';

//utilisation de session_start(pour gérer la connexion au serveur)
session_start();
//Analyse de l'URL avec parse_url() et retourne ses composants
$url = parse_url($_SERVER['REQUEST_URI']);
//test si l'url posséde une route sinon on renvoi à la racine
$path = $url['path'] ??  '/';

//Récupération de la méthode HTTP
$requestMethod = $_SERVER['REQUEST_METHOD'];

//importer le UserController
use App\Controller\UserController;

$userController = new UserController();

//routeur
switch (trim($path,BASE_URL)) {
    case '':
        echo 'Bienvenue';
        break;
    case 'user':
        if ($requestMethod === 'GET') {
            echo "Afficher tous les utilisateurs";
        }
        else if ($requestMethod === 'POST') {
            $userController->save();
        }
        else {
            echo "Methode invalide";
        }
        break;
    case 'user/id':
        if ($requestMethod === 'GET') {
            echo "Afficher un utilisateur par son id";
        }
        else if ($requestMethod === 'PUT') {
            echo "modifier un utilisateur par son id";
        }
        else if ($requestMethod === 'DELETE') {
            echo "supprimer un utilisateur par son id";
        }
        else {
            echo "Methode invalide";
        }
        break;
    case '/tpapi/user/gettoken':
        if ($requestMethod === 'POST') {
            echo "Récupérer le token";
        }
        else {
            echo "Methode invalide";
        }
        break;
    case '/tpapi/user/verifytoken':
        if ($requestMethod === 'GET') {
            echo "Vérifier le token";
        }
        else {
            echo "Methode invalide";
        }
        break;
    default:
        echo "erreur 404";
        break;
}
