<?php

namespace App\Utils;

class Tools
{
    //fonction pour nettoyer une chaine de caractère
    public static function cleanInput(string $input): string{
        $input = trim($input);
        $input = strip_tags($input);
        $input = htmlspecialchars($input, ENT_NOQUOTES);
        return $input;
    }

    //fonction pour nettoyer un tableau de données
    public static function cleanData(array $data): array{
        $cleanData = [];
        foreach ($data as $key => $value) {
            $cleanData[$key] = self::cleanInput($value);
        }
        return $cleanData;
    }

    //fonction pour récupérer le body de la requête
    public static function getBody()
    {
        return json_decode(file_get_contents('php://input'));
    }
}
