<?php

namespace App\Repository;

use App\Utils\Bdd;
use App\Model\User;

class UserRepository
{
    //Attributs
    private static \PDO $bdd;

    //Constructeur
    public function __construct()
    {
        self::$bdd = Bdd::connexion();
    }


    //Méthode 
    public function add(User $user): User
    {
        try {
            //variable (utilisateur)
            $lastname = $user->getLastname();
            $firstname = $user->getFirstname();
            $email = $user->getEmail();
            $password = $user->getPassword();
            //1 requête INSERT
            //requête SQL
            $sql = "INSERT INTO user(lastname, firstname, email, `password`) VALUE(?,?,?,?)";
            //préparation de la requête
            $request = self::$bdd->prepare($sql);
            //bindparam
            $request->bindParam(1, $lastname, \PDO::PARAM_STR);
            $request->bindParam(2, $firstname, \PDO::PARAM_STR);
            $request->bindParam(3, $email, \PDO::PARAM_STR);
            $request->bindParam(4, $password, \PDO::PARAM_STR);
            $request->execute();

            //2 requête SELECT (réccupération de l'id)
            //requête SQL
            $sql2 = "SELECT u.id, u.lastname, u.firstname, u.email FROM user AS u 
            WHERE u.email = ? ORDER BY id DESC LIMIT 1";
            $request2 = self::$bdd->prepare($sql2);
            $request2->bindParam(1, $email, \PDO::PARAM_STR);
            $request2->execute();
            $request2->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, User::class);
            $user = $request2->fetch();
        } catch (\PDOException $e) {
            die("Error" . $e->getMessage());
        }
        return $user;
    }
}
