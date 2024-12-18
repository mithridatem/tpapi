<?php

namespace App\Controller;

use App\Utils\Tools;

class HomeController
{
    public function __construct() {}
    public function home()
    {
        Tools::JsonResponse(["Message" => "Bienvenue sur notre API"], 200);
    }
    public function test($message)
    {
        Tools::JsonResponse(["Message" => $message], 200);
    }
}
