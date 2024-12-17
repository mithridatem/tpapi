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

//importer les classes
use App\Controller\UserController;
use App\Utils\Tools;

//instance du controller
$userController = new UserController();

//routeur
switch (trim($path, BASE_URL )) {
    case '':
        Tools::JsonResponse(["Message"=>"Bienvenue sur notre API"], 200);
        break;
    case 'user':
        //Test de la méthode de la requête
        if($requestMethod === 'POST') {
            $userController->save();
        }
        else if($requestMethod === 'GET') {
            Tools::JsonResponse(["Message"=>"Listes de tous les utilsiateurs"], 200);
        }
        else if ($requestMethod === 'DELETE') {
            Tools::JsonResponse(["Message"=>"Suppression de tous les utilisateurs"], 200);
        }
        else {
            Tools::JsonResponse(["Message"=>"Méthode non autorisée"], 405);
        }
        break;
    default:
        Tools::JsonResponse(["Message"=>"Erreur 404"], 404);
        break;
}
