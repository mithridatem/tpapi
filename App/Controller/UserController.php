<?php

namespace App\Controller;

use App\Model\User;
use App\Repository\UserRepository;
use App\Utils\Tools;
use App\Service\JWTService;

class UserController
{
    //Attributs
    private readonly UserRepository $repository;
    private readonly JWTService $jwtService;

    //Constructeur
    public function __construct()
    {
        $this->repository = new UserRepository();
        $this->jwtService = new JWTService();
    }

    //Méthode pour créer un utilisateur
    public function save()
    {
        $message = [];
        $statusCode = 200;
        //récupérer le corp de la requête
        $json = Tools::getRequestBody();
        //tester si le json est vide
        if ($json == '""') {
            $message = ["message" => "Le corps de la requête est vide"];
            $statusCode = 400;
        }
        //décoder le json en tableau associatif
        $dataUser = json_decode($json, true);
        //nettoyer le tableau
        $dataUser = Tools::sanitizeArray($dataUser);
        //créer un objet User et on l'hydrate
        $user = (new User())->hydrate($dataUser);
        //on hash le password
        $user->hashPassword();
        //on récupérer si le compte existe déjà
        $test = $this->repository->findEmail($user->getEmail());
        //tester si le compte existe déjà
        if ($test) {
            $message = ["Message" => "Cet email existe déjà"];
            $statusCode = 400;
        }
        //Sinon on crée le compte
        else {
            //on crée le compte
            $newUser = $this->repository->add($user);
            $message = $newUser->toArray();
        }
        //retourne une Réponse JSON
        Tools::JsonResponse($message, $statusCode);
    }

    //Méthode pour afficher tous les utilisateurs
    public function showAll(): void
    {
        $message = [];
        $statusCode = 200;
        //Tableau d'objets User
        $users = $this->repository->findAll();
        //Tester si le tableau est vide
        if (empty($users)) {
            $message = ["Message" => "Aucun utilisateur trouvé"];
            $statusCode = 404;
        } else {
            //Stocker les tableaux d'utilisateurs
            $userTab = [];
            //boucle sur le tableau d'objets User
            foreach ($users as $user) {
                //ajouter au tableau $userTab la objet transformé en tableau
                $userTab[] = $user->toArray();
            }
            $message = $userTab;
        }

        //retourne une Réponse JSON
        Tools::JsonResponse($message, $statusCode);
    }

    //Méthode pour afficher un utilisateur par son id
    public function showUser(): void
    {
        $message = [];
        $statusCode = 200;
        //Tester si les paramétres de l'url existent (get => id)
        if (isset($_GET["id"])) {
            $user = $this->repository->find($_GET["id"]);
            //test si l'utilisateur n'existe pas
            if (!$user) {
                $message = ["Message" => "Utilisateur non trouvé"];
                $statusCode = 404;
            }
            //Sinon on retourne l'utilisateur
            else {
                $message = $user->toArray();
            }
        }
        //sinon
        else {
            //retourner une Reponse JSON avec un message et une erreur 404
            $message = ["Erreur" => "Le paramètre id n'existe pas"];
            $statusCode = 404;
        }
        Tools::JsonResponse($message, $statusCode);
    }

    //Méthode pour Récupérer le token JWT
    public function getUserToken()
    {
        $message = [];
        $statusCode = 200;
        //récupérer le corp de la requête
        $json = Tools::getRequestBody();
        //tester si le json est vide
        if ($json == '""') {
            $message = ["message" => "Le corps de la requête est vide"];
            $statusCode = 400;
        }
        //Traitement du json
        else {
            //décoder le json en tableau associatif
            $tab = json_decode($json, true);
            //nettoyer le tableau
            $tab = Tools::sanitizeArray($tab);
            //verif authentification
            if (
                isset($tab["email"]) and
                isset($tab["password"]) and
                $this->jwtService->authentification($tab["email"], $tab["password"])
            ) {
                //génération du token
                $token = $this->jwtService->genToken($tab["email"]);
                $message = ["Token" => $token];
            }
            //Sinon je retourne une erreur
            else {
                $message = ["Message" => "Email et ou mot de passe incorrect ou absent"];
                $statusCode = 403;
            }
        }
        //retourne une Réponse JSON
        Tools::JsonResponse($message, $statusCode);
    }

    //Méthode pour vérifier le token JWT
    public function verifyUserToken(?string $bearer)
    {
        $message = [];
        $statusCode = 200;
        //tester si le token est vide
        if ($bearer == null) {
            $message = ["Message" => "Token absent"];
            $statusCode = 400;
        }
        //sinon
        else {
            //recupération de la verif du token
            $verif = $this->jwtService->verifyToken($bearer);
            //tester si le token est valide
            if ($verif === true) {
                $message = ["Message" => "Token valide"];
            }
            //Sinon je retourne l'erreur du token
            else {
                $message = ["Message" => $verif];
                $statusCode = 403;
            }
        }
        //retourne une Réponse JSON
        Tools::JsonResponse($message, $statusCode);
    }
}
