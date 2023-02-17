<h1><?= $params['post']->title ?></h1>
/** echo de la variable params avec le post initialisé */
<div>
    <?php foreach ($params['post']->getTags() as $tag) : ?>
        <span class="badge badge-info"><?= $tag->name ?></span>
    <?php endforeach ?>
</div>
<p><?= $params['post']->content ?></p>
<a href="<?= HREF_ROOT ?>posts" class="btn btn-secondary">Retourner en arrière</a>