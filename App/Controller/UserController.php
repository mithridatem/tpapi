<?php

namespace App\Controller;

use App\Repository\UserRepository;

class UserController
{
    private UserRepository $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function save(){
        echo 'ajout utilisateur depuis le controller';
    }
}
