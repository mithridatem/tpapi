<?php

namespace App\Router;

class Route
{
    //Attributs
    private ?string $url;
    private ?string $httpMethod;
    private ?string $controller;
    private ?string $method;
    private ?string $param;

    //Méthodes
    //Constructeur
    public function __construct(string $url, string $httpMethod, string $controller, string $method, ?string $param = null)
    {
        $this->url = $url;
        $this->httpMethod = $httpMethod;
        $this->controller = $controller;
        $this->method = $method;
        $this->param = $param;
    }
}
