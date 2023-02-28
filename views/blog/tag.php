<h1><?= $params['tag']->name ?></h1>

<?php foreach ($params['tag']->getPosts() as $post) : ?>
    <div class="card mb-3">
        <div class="card-body">
            <!-- le lien ci-dessous permet d'établir un chemin vers /posts/ l'id d'un post -->
            <a><a href="<?= HREF_ROOT ?>posts/<?= $post->id ?>"><?= $post->title ?></a></a>
        </div>
    </div>
<?php endforeach ?>