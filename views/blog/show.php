<h1><?= $params['post']->title ?></h1>
<!-- l'echo au dessus donne l'objet souhaité et l'element grâce au flechage echo de la variable params avec le post initialisé  -->
<!-- div permettant de rendre les tags visibles sur un article -->
<div>
    <?php foreach ($params['post']->getTags() as $tag) : ?>
        <span class="badge badge-info"><?= $tag->name ?></span>
    <?php endforeach ?>
</div>
<!-- l'echo affiche le contenu de l'objet en désignant la variable 'content' -->
<p><?= $params['post']->content ?></p>
<!-- bouton pour revenir en arrière -->
<a href="<?= HREF_ROOT ?>posts" class="btn btn-secondary">Retourner en arrière</a>