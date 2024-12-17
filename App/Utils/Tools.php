<?php

namespace App\Utils;

class Tools
{
    public static function JsonResponse(array $data, int $status = 200) : void {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        http_response_code($status);
        echo json_encode($data);
    }
}
