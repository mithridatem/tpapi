<?php

namespace App\Utils;

class Tools
{
    //Méthode qui retourne une réponse JSON
    public static function JsonResponse(array $data, int $status = 200): void
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        http_response_code($status);
        echo json_encode($data);
    }
    
    //Méthode qui convertie une chaine de caractéres en UTF-8
    public static function convertUtf8(string $chaine): string
    {
  
        return mb_convert_encoding(
            $chaine,
            "UTF-8",
            mb_detect_encoding($chaine)
        );
    }

    //Méthode qui nettoie une chaine de caractéres
    public static function cleanInput(string $input): string{
        $input = trim($input);
        $input = strip_tags($input);
        $input = htmlspecialchars($input, ENT_NOQUOTES);
        return $input;
    }

    //Méthode qui nettoie un tableau de données
    public static function cleanData(array $data): array{
        $cleanData = [];
        foreach ($data as $key => $value) {
            $cleanData[$key] = self::cleanInput($value);
        }
        return $cleanData;
    }
    //fonction pour récupérer le body de la requête
    public static function getBody(): bool|string        
    {
        return file_get_contents('php://input');
    }
}
