<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Tag;

/** classe des actions disponibles du controller  */
class BlogController extends Controller
{

    public function welcome()
    {
        /** welcome()
         * le fait de mettre un '.' "mime" le '/' que l'on peut retrouver dans Laravel par ex
         * affiche la page welcome.php
         */
        return $this->view('blog.welcome');
    }
    /** fonction permettant d'indiquer la homepage et ses posts  */
    public function index()
    {
        $post = new Post($this->getDB());
        $posts = $post->all();
        /** affiche la page index.php 
         * * 'posts' dans compact est la variable 'posts' initialisé auparavant
         */
        return $this->view('blog.index', compact('posts'));
    }
    /** fonction permettant d'afficher les posts par ID */
    public function show(int $id)
    {
        $post = new Post($this->getDB());
        $post = $post->findById($id);
        /** return $this
         *  compact() -> fait un array
         * affiche la page show.php
         * 'post' dans compact est la variable 'post' initialisé auparavant
         */
        return $this->view('blog.show', compact('post'));
    }

    public function tag(int $id)
    {
        $tag = (new Tag($this->getDB()))->findById($id);
        /** 'tag' dans compact est la variable 'tag' initialisé auparavant*/
        return $this->view('blog.tag', compact('tag'));
    }
}
