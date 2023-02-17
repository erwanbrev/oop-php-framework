<?php

namespace Router;

use App\Exceptions\NotFoundException;

class Router
{

    public $url;
    public $routes = [];

    public function __construct($url)
    {
        /** trim -> retire les '/' en début et en fin d'URL */
        $this->url = trim($url, '/');
    }

    public function get(string $path, string $action)
    {
        /** clé GET permettant de recueillir la route Pushée ayant pour param le path et une action   */
        $this->routes['GET'][] = new Route($path, $action);
    }

    public function post(string $path, string $action)
    {
        /** clé POST */
        $this->routes['POST'][] = new Route($path, $action);
    }
    /** permet de boucler sur nos routes avec un foreach */
    public function run()
    {
        /** supervariable SERVER pour avoir un GET ou POST selon la requête définie en amont */
        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            /** prend l'url en param */
            if ($route->matches($this->url)) {
                /** execute appelle le bon controller avec la bonne fonction */
                return $route->execute();
            }
        }
        /** exception si la page demandée n'est pas existante */
        throw new NotFoundException("La page demandée est introuvable.");
    }
}
