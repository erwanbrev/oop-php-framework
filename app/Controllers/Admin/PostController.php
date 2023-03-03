<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;

/** PostController
 * est le fichier de controleur pour les articles
 */
class PostController extends Controller
{
    /** index
     * sert à faire le listing des articles
     * this->getDB dans le Post sert à faire un lien vers la base de donnée
     * -> all() récupère tous les postes
     * lien vers une vue qui contiendrait un admin/post/index.php
     */
    public function index()
    {
        $this->isAdmin();

        $posts = (new Post($this->getDB()))->all();

        return $this->view('admin.post.index', compact('posts'));
    }

    public function create()
    {
        $this->isAdmin();

        $tags = (new Tag($this->getDB()))->all();

        return $this->view('admin.post.form', compact('tags'));
    }

    public function createPost()
    {
        $this->isAdmin();

        $post = new Post($this->getDB());

        $tags = array_pop($_POST);

        $result = $post->create($_POST, $tags);

        if ($result) {
            return header('Location:' . HREF_ROOT . 'admin/posts');
        }
    }
    /** edit() 
     * fonction edit permettant de récupérer les posts et les tags existants pour les rendre à l'écran, pour la lecture du développeur
     */
    public function edit(int $id)
    {
        // var_dump("Model edit:", $id);
        $this->isAdmin();

        $post = (new Post($this->getDB()))->findById($id);
        $tags = (new Tag($this->getDB()))->all();

        //var_dump("Model edit:", $post);
        //var_dump("Model edit:", $tags);
        // editer un post par le chemin admin/post/form
        return $this->view('admin.post.form', compact('post', 'tags'));
        //return $this->view('admin.post.form', compact('post'));
    }

    public function update(int $id)
    {
        $this->isAdmin();

        $post = new Post($this->getDB());

        // var_dump("PostController update:",$_POST);
        /** retirer et mettre dans une variable avec array_pop() */
        // sépare et met à suivre deux array()
        $tags = array_pop($_POST);
        // var_dump("PostController update:",$_POST, $tags);
        $result = $post->update($id, $_POST, $tags);

        if ($result) {
            return header('Location: ' . HREF_ROOT . 'admin/posts');
        }
    }
    /** fonction permettant de supprimer un article dans la base de donnée */
    public function destroy(int $id)
    {
        $this->isAdmin();

        // $post = new Post($this->getDB()))->findById($id);
        $post = new Post($this->getDB());
        $result = $post->destroy($id);

        if ($result) {
            return header('Location: ' . HREF_ROOT . 'admin/posts');
        }
    }
}
