<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Tag;

class BlogController extends Controller
{

    public function welcome()
    {
        /** le fait de mettre un '.' "mime" le '/' que l'on peut retrouver dans Laravel par ex */
        return $this->view('blog.welcome');
    }
    /** fonction permettant d'indiquer la homepage et ses posts  */
    public function index()
    {
        $post = new Post($this->getDB());
        $posts = $post->all();

        return $this->view('blog.index', compact('posts'));
    }
    /** fonction permettant d'afficher les posts par ID */
    public function show(int $id)
    {
        $post = new Post($this->getDB());
        $post = $post->findById($id);
        /** compact() -> fait un array */
        return $this->view('blog.show', compact('post'));
    }

    public function tag(int $id)
    {
        $tag = (new Tag($this->getDB()))->findById($id);

        return $this->view('blog.tag', compact('tag'));
    }
}
