<?php

use Router\Router;
use App\Exceptions\NotFoundException;


require '../vendor/autoload.php';
//Indiquer la racine de votre site ici HREF_ROOT. Si votre site en localhost est à la racine indiquer /
define('HREF_ROOT', 'http://localhost:8888/1-exos/6-Blog/PooBlog/');
// Not in use now. J'ai utilisé cette constante pour trouvé des bugs dans le les formulaire. Est peut remplacer HREF_ROOT
define('VIEWS_FORM_ROOT', '../../../');

/** Constante pointant vers les vues(VIEWS)
 * dirname(__DIR__) -> appelle le dossier dans lequel nous sommes, dirname -> retour en arriere '../'
 * 'views' -> dossier du même nom
 */
define('VIEWS', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);
//define('SCRIPTS', dirname($_SERVER['SCRIPT_NAME']) . DIRECTORY_SEPARATOR); // Code original
/** constante pointant vers les scripts 
 * HREF_ROOT -> dossier d'origine des scripts
 * Informations concernant l'accès à la BDD
 */
define('SCRIPTS', HREF_ROOT . 'public/');
define('DB_NAME', 'myappBlog');
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PWD', '');

$router = new Router($_GET['url']);
/** intégration du namespace en début de lien avec : 'App\Controllers\ 
 * '/' -> une page appelée 'welcome'
 * '/posts' -> liste de tous les articles
 * '/posts/:id' -> fonction SHOW
 */
$router->get('/', 'App\Controllers\BlogController@welcome');
$router->get('/posts', 'App\Controllers\BlogController@index');
$router->get('/posts/:id', 'App\Controllers\BlogController@show');
$router->get('/tags/:id', 'App\Controllers\BlogController@tag');

$router->get('/login', 'App\Controllers\UserController@login');
$router->post('/login', 'App\Controllers\UserController@loginPost');
$router->get('/logout', 'App\Controllers\UserController@logout');

$router->get('/admin/posts', 'App\Controllers\Admin\PostController@index');
$router->get('/admin/posts/create', 'App\Controllers\Admin\PostController@create');
$router->post('/admin/posts/create', 'App\Controllers\Admin\PostController@createPost');
$router->post('/admin/posts/delete/:id', 'App\Controllers\Admin\PostController@destroy');
$router->get('/admin/posts/edit/:id', 'App\Controllers\Admin\PostController@edit');
$router->post('/admin/posts/edit/:id', 'App\Controllers\Admin\PostController@update');

try {
    $router->run();
} catch (NotFoundException $e) {
    return $e->error404();
}
