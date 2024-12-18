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

//Récupération de la méthode de la requête
$requestMethod = $_SERVER['REQUEST_METHOD'];

//Récupération du token JWT
$bearer = isset($_SERVER['HTTP_AUTHORIZATION']) ? preg_replace(
    '/Bearer\s+/',
    '',
    $_SERVER['HTTP_AUTHORIZATION']
) : null;

//importer les classes
use App\Controller\UserController;
use App\Utils\Tools;

//instance des Controllers
$userController = new UserController();

//routeur
switch (substr($path, strlen(BASE_URL))) {
        //Endpoint Home
    case '':
        Tools::JsonResponse(["Message" => "Bienvenue sur notre API"], 200);
        break;
        //Endpoint User
    case 'user':
        //Test de la méthode GET
        if ($requestMethod === 'GET') {
            $userController->showAll();
        }
        //Test de la méthode POST
        else if ($requestMethod === 'POST') {
            $userController->save();
        }
        //Test de la méthode DELETE
        else if ($requestMethod === 'DELETE') {
            Tools::JsonResponse(["Message" => "Suppression de tous les utilisateurs"], 200);
        }
        //Sinon la méthode n'est pas autorisée
        else {
            Tools::JsonResponse(["Message" => "Méthode non autorisée"], 405);
        }
        break;
        //Endpoint User/id
    case 'user/id':
        //Test de la méthode GET
        if ($requestMethod === 'GET') {
            $userController->showUser();
        }
        //Test de la méthode PATCH
        else if ($requestMethod === 'PATCH') {
            Tools::JsonResponse(["Message" => "Utilisateur mis à jour par son id"], 200);
        }
        //Test de la méthode DELETE
        else if ($requestMethod === 'DELETE') {
            Tools::JsonResponse(["Message" => "Utilisateur supprimé par son id"], 200);
        }
        //Sinon la méthode n'est pas autorisée
        else {
            Tools::JsonResponse(["Message" => "Méthode non autorisée"], 405);
        }
        break;
        //Endpoint token JWT
    case 'user/token':
        //Test de la méthode POST(recupération du token JWT)
        if ($requestMethod === 'POST') {
            $userController->getUserToken();
        }
        //test de la méthode GET (vérification du token JWT)
        else if ($requestMethod === 'GET') {
            $userController->verifyUserToken($bearer);
        }
        //Sinon la méthode n'est pas autorisée
        else {
            Tools::JsonResponse(["Message" => "Méthode non autorisée"], 405);
        }
        break;
    default:
        Tools::JsonResponse(["Message" => "Erreur 404"], 404);
        break;
}
