<?php

namespace App\Model;

class User
{

    //Attributs
    private ?int $id;
    private ?string $lastname;
    private ?string $firstname;
    private ?string $email;
    private ?string $password;
    
    //Constructeur

    public function __construct(){}


    //Getters
    public function getId(): null|int {
        return $this->id;
    }

    public function getLastname() :null|string{
        return $this->lastname;
    }

    public function getFirstname() :null|string{
        return $this->firstname;
    }

    public function getEmail() : null|string {
        return $this->email;
    }

    public function getPassword() :null|string{
        return $this->password;
    }

    //Setters
    public function setId(?int $id) :self {
        $this->id = $id;
        return $this;
    }

    public function setLastname(?string $lastname) :self {
        $this->lastname = $lastname;
        return $this;
    }

    public function setFirstname(?string $firstname) :self {
        $this->firstname = $firstname;
        return $this;
    }

    public function setEmail(?string $email) :self {
        $this->email = $email;
        return $this;
    }

    public function setPassword(?string $password) :self {
        $this->password = $password;
        return $this;
    }

    //Méthodes
    //Méthodes qui hash le password avec bcript	
    public function hashPassword() :void {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    }

    //Méthode qui vérifie le mot de passe
    public function verifPassword(string $clear) :bool {
        return password_verify($clear, $this->password);
    }

    //Méthode qui retourne le nom et prénom de l'objet User
    public function __tostring() :string {
        return $this->firstname . ' ' . $this->lastname; 
    }

}
