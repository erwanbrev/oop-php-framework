<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class NotFoundException extends Exception
{
    /** __construct
     * attend le message
     * throwable -> tout ce qui peut etre jeter avec l'appel de 'throw'
     *  */
    public function __construct($message = "", $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    /** error404
     * fonction affichant un message précis dans la fonction construct ci-dessus
     * http_response précise le code d'erreur
     */
    public function error404()
    {
        // echo VIEWS;
        // die();
        http_response_code(404);

        require VIEWS . 'errors/404.php';
    }
}
