<?php

namespace Router;

/** classe permettant de définir une ou des actions concernant les routes, qui seront appelées dans Router.php*/

use Database\DBConnection;

class Route
{

    public $path;
    public $action;
    public $matches;

    public function __construct($path, $action)
    {
        /** dans path tu stockes le path */
        $this->path = trim($path, '/');
        /** dans action tu stockes l'action venant d'être envoyée */
        $this->action = $action;
    }

    /**
     * Summary of matches
     * Matches renvoie true si le l'url est une route valable
     * false si elle n'est pas valable
     * @param string $url
     * @return bool
     */
    public function matches(string $url)
    {
        /** on cherche à remplacer tout ce qui commence par :
         * ':'
         * et qui serait après un caractere alphanum
         * '\w' est un raccourci pour désigner tout ce qui n'est pas une lettre, un chiffre ou un trait de soulignement.
         * '^/' signifie "qui ne soit pas un slash"
         *  '+' signifie une chose qui peut etre répetée plusieurs fois
         */
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);
        /** on englobe le chemin par un '^' et '$' pour montrer que l'on veut passer au preg_match tout le path */
        $pathToMatch = "#^$path$#";

        /** on veut tester cette condition dans l'url 
         * matches est un tableau contenant le param voulu
         * en 1ere clé il aura l'url 
         * en 2eme clé l'ID d'un post
         */
        if (preg_match($pathToMatch, $url, $matches)) {
            /** on enregistre matches dans une variable du même nom */
            $this->matches = $matches;
            return true;
        } else {
            return false;
        }
    }
    /** fonction ayant besoin de récuperer le controller et une action  */
    public function execute()
    {
        /** '@' -> délimiteur de l'action
         * on explose par l'@ -> l'action 
         */
        $params = explode('@', $this->action);
        /** premiere clé
        */
        $controller = new $params[0](new DBConnection(DB_NAME, DB_HOST, DB_USER, DB_PWD));
        /** deuxieme clé */
        $method = $params[1];
        /** terner vérifiant :
         * si on a un matches [1] de SET
         * ? -> est-ce que c'est le cas ?
         * si OUI -> appel de la méthode contenant le param 
         * si NON on appelle la méthode mais sans param
         */
        return isset($this->matches[1]) ? $controller->$method($this->matches[1]) : $controller->$method();
    }
}
