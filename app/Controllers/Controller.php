<?php

namespace App\Controllers;

use Database\DBConnection;

abstract class Controller
{

    protected $db;

    public function __construct(DBConnection $db)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->db = $db;
    }
    /** 
     * 1er argument => chemin vers les vues
     * 2eme // => tableau nulle par défaut 
     */
    protected function view(string $path, array $params = null)
    {
        ob_start();
        $path = str_replace('.', DIRECTORY_SEPARATOR, $path);
        // var_dump("Controller view",VIEWS , $path);
        //die();
        require VIEWS . $path . '.php';
        $content = ob_get_clean();
        require VIEWS . 'layout.php';
    }

    protected function getDB()
    {
        return $this->db;
    }

    protected function isAdmin()
    {
        if (isset($_SESSION['auth']) && $_SESSION['auth'] === 1) {
            return true;
        } else {
            return header('Location:' . HREF_ROOT . 'login');
        }
    }
}
