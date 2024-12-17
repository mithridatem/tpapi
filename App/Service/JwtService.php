<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Utils\Tools;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    //Constructeur
    public function __construct(
        private readonly string $key,
        private readonly UserRepository $userRepository
    ) {
        $this->key = TOKEN_SECRET_KEY;
        $this->userRepository = new UserRepository();
    }

    //Méthodes
    //Authentification
    public function authentification(string $email, string $password)
    {
        $email = Tools::sanitize($email);
        $password = Tools::sanitize($password);
        $user = $this->userRepository->findEmail($email);
        if ($user) {
            if ($user->verifPassword($password)) {
                return true;
            }
        }
        return false;
    }

    //Génération du token
    public function genToken(string $email): string
    {
        //construction du JWT
        //Variables pour le token
        $issuedAt   = new \DateTimeImmutable();
        $expire     = $issuedAt->modify('+' . TOKEN_VALIDITY . ' minutes')->getTimestamp();
        $serverName = "your.domain.name";
        $user = $this->userRepository->findEmail($email);
        //Contenu du token
        $payload = [
            'iat'  => $issuedAt->getTimestamp(),         // Timestamp génération du token
            'iss'  => $serverName,                       // Serveur
            'nbf'  => $issuedAt->getTimestamp(),         // Timestamp empécher date antérieure
            'exp'  => $expire,
            'userId' => $user->getId(),                           // Timestamp expiration du token
            'userLastname' => $user->getLastname(),
            'userFirstname' => $user->getFirstname()
        ];
        //retourne le JWT token encode
        $token = JWT::encode(
            $payload,
            $this->key,
            'HS512'
        );
        return $token;
    }

    //Vérification du token
    public function verifyToken(string $jwt): bool|string
    {

        try {
            //Décodage du token
            JWT::decode($jwt, new Key($this->key, 'HS512'));
            return true;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    //Décodage des données du token
    public function getDataFromToken(string $jwt): object
    {
        //Décodage du token
        $token = JWT::decode($jwt, new Key($this->key, 'HS512'));
        return $token;
    }

    //Génération d'un token fake (pour les tests)
    public function genFakeToken(): string
    {
        //construction du JWT
        //Variables pour le token
        $issuedAt   = new \DateTimeImmutable();
        $expire     = $issuedAt->modify('+' . TOKEN_VALIDITY . ' minutes')->getTimestamp();
        $serverName = "your.domain.name";
        $user = "fake";
        $userLastname   = "Doe";
        $userFirstname   =  "John";
        $id   =  1;
        //Contenu du token
        $payload = [
            'iat'  => $issuedAt->getTimestamp(),         // Timestamp génération du token
            'iss'  => $serverName,                       // Serveur
            'nbf'  => $issuedAt->getTimestamp(),         // Timestamp empécher date antérieure
            'exp'  => $expire,                           // Timestamp expiration du token
            'userLastname' => $userLastname,
            'userFirstname' => $userFirstname,
            'userId' => $id,
        ];
        //retourne le token JWT
        $token = JWT::encode(
            $payload,
            $this->key,
            'HS512'
        );
        return $token;
    }
}
