<?php

namespace App\Utils;

class Tools
{
    
    public static function JsonResponse(array $data, int $status = 200): void
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        http_response_code($status);
        echo json_encode($data);
    }

    public static function convertUtf8(string $chaine): string
    {
  
        return mb_convert_encoding(
            $chaine,
            "UTF-8",
            mb_detect_encoding($chaine)
        );
    }
}
