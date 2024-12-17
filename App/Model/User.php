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
    public function getId(): int | null{
        return $this->id;
    }

    public function getLastname() :string|null {
        return $this->lastname;
    }

    public function getFirstname() : string|null{
        return $this->firstname;
    }

    public function getEmail() : string|null {
        return $this->email;
    }

    public function getPassword() :string |null{
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
    //Méthodes qui hash le password avec Bcrypt	
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

    //méthode qui hydrate en objet User
    public function hydrate(array $data) :self {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    //Méthode qui deshydrate l'objet User en tableau
    public function toArray() :array {
        $user = [];
        foreach ($this as $key => $value) {
            $method = 'get' . ucfirst($key);
            if (method_exists($this, $method)) {
                $user[$key] = $this->$method();
            }
        }
        return $user;         
    }
}
