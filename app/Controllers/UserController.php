<?php

namespace App\Controllers;

use App\models\User;
use App\Validation\Validator;

class UserController extends Controller
{

    public function login()
    {
        return $this->view('auth.login');
    }

    public function loginPost()
    {
        /** validator
         * tableau avec clé ->username / password
         * required -> nécessaire
         * min:3 -> minimum 3 caracteres
         */
        $validator = new Validator($_POST);
        $errors = $validator->validate([
            'username' => ['required', 'min:3'],
            'password' => ['required']
        ]);

        if ($errors) {
            $_SESSION['errors'][] = $errors;
            header('Location:' . HREF_ROOT . ' login');
            exit;
        }

        $user = (new User($this->getDB()))->getByUsername($_POST['username']);
        /** password_verify
         * vérifie si pwd correspond à un hashage
         * 1 argument -> pwd saisie / 2 argument -> pwd BDD
         * ?success=true => si elle existe et bien -> message
         * sinon redirection page login
         */
        if (password_verify($_POST['password'], $user->password)) {
            $_SESSION['auth'] = (int) $user->admin;
            return header('Location: ' . HREF_ROOT . 'admin/posts?success=true');
        } else {
            return header('Location: ' . HREF_ROOT . 'login');
        }
    }

    /** logout
     * deconnexion via destruction de session
     */
    public function logout()
    {
        session_destroy();

        return header('Location:' . HREF_ROOT . ' /');
    }
}
