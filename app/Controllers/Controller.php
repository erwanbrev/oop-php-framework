<?php

namespace App\Controllers;

use Database\DBConnection;

/** class qui n'est pas directement instanciée donc ABSTRACT */
abstract class Controller
{
    /** la connexion se ferait que dans les classes ENFANTS donc PROTECTED ONLY */
    protected $db;
    /** a construction du controller tu vas prendre une instance de db avec DBConnection */
    public function __construct(DBConnection $db)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        /** on y stocke la connexion à la BDD */
        $this->db = $db;
    }
    /** 
     * 1er argument => chemin vers les vues
     * 2eme // => tableau nulle par défaut 
     */
    protected function view(string $path, array $params = null)
    {
        /** demarre le systeme de buffering */
        ob_start();
        /** on recherche les eventuels '.' et on les remplace par un directory_separator */
        $path = str_replace('.', DIRECTORY_SEPARATOR, $path);
        // var_dump("Controller view",VIEWS , $path);
        //die();
        /** besoin d'une vue dans le dossier view et on concatene le path avec extension obligatoire
         * vues => .php
         */
        require VIEWS . $path . '.php';
        /** stocker notre vue quelque part dans notre variable */
        $content = ob_get_clean();
        require VIEWS . 'layout.php';
    }

    /** fonction permettant de retourner le return ci-dessous */
    protected function getDB()
    {
        return $this->db;
    }
    /** s'il est admin alors
     * return true 
     * sinon 
     * return page login
     */
    protected function isAdmin()
    {
        if (isset($_SESSION['auth']) && $_SESSION['auth'] === 1) {
            return true;
        } else {
            return header('Location:' . HREF_ROOT . 'login');
        }
    }
}
