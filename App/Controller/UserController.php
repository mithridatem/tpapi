<?php

namespace App\Controller;

use App\Model\User;
use App\Repository\UserRepository;
use App\Utils\Tools;

class UserController
{
    private UserRepository $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    //Méthode pour créer un utilisateur
    public function save()
    {
        //récupérer le body de la requête
        $json = Tools::getBody();
        //tester si le json est vide
        if($json == '""'){
            //retourne une Réponse JSON
            Tools::JsonResponse(["message"=>"Le corps de la requête est vide"], 400);
            exit;
        }
        //décoder le json en tableau associatif
        $tab = json_decode($json, true);
        //nettoyer le tableau
        $tab = Tools::sanitizeArray($tab);
        //créer un objet User
        $user = new User();
        //on hydrate l'objet User
        $user->hydrate($tab);
        //on hash le password
        $user->hashPassword();
        //on récupérer si le compte existe déjà
        $test = $this->repository->findEmail($user->getEmail());
        //tester si le compte existe déjà
        if($test) {
            //retourne une Réponse JSON
            Tools::JsonResponse(["Message"=>"Cet email existe déjà"], 400);
            exit;
        }
        //on crée le compte
        $newUser = $this->repository->add($user);
        //retourne une Réponse JSON
        Tools::JsonResponse(["Utilisateur"=>$newUser->toArray()], 200);
    }

    //Méthode pour afficher tous les utilisateurs
    public function showAll() :void {
        //Tableau d'objets User
        $users = $this->repository->findAll();
        //Tester si le tableau est vide
        if(empty($users)) {
            //retourne un  Réponse JSON
            Tools::JsonResponse(["Message"=>"Aucun utilisateur trouvé"], 404);
            exit;
        }
        //Stocker les tableaux d'utilisateurs
        $userTab = [];
        //boucle sur le tableau d'objets User
        foreach ($users as $user) {
            //ajouter au tableau $userTab la objet transformé en tableau
            $userTab[] = $user->toArray();
        }
        //retourne une Réponse JSON
        Tools::JsonResponse(["Utilisateurs"=>$userTab], 200);
    }

    //Méthode pour afficher un utilisateur par son id
    public function showUser() : void  
    {
        //Tester si les paramétres de l'url existent (get => id)
        if (isset($_GET["id"])) {
            $user = $this->repository->find($_GET["id"]);
            //test si l'utilisateur n'existe pas
            if(!$user) {
                //retourner une Reponse JSON avec un message et une erreur 404
                Tools::JsonResponse(["Message"=>"Utilisateur non trouvé"], 404);
                exit;
            }
            Tools::JsonResponse(["users"=>$user->toArray()], 200);
        } 
        //sinon
        else {
            //retourner une Reponse JSON avec un message et une erreur 404
            Tools::JsonResponse(["Erreur"=>"Le paramètre n'existe pas"], 400);
        }
    }
}
