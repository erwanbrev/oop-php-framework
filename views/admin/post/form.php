<script language=javascript>
    function submitPostLink() {
        document.postlink.submit();
    }
</script>

<h1><?= $params['post']->title ?? 'Créer un nouvel article' ?></h1>

<?php
// var_dump($_REQUEST);
// $_REQUEST['url'] = "http://localhost/".$_REQUEST['url'];
// var_dump($_REQUEST);
?>
<!-- si un post est crée alors edit sinon créer -->
<form method="POST" name="postlink" action="<?= isset($params['post']) ? HREF_ROOT . "admin/posts/edit/{$params['post']->id}" :  "../../admin/posts/create" ?>">
    <!-- creation de la page de modification d'un post -->
    <div class="form-group">
        <label for="title">Titre de l'article</label>
        <!-- 
            *$params['post']->title = vient chercher le titre présent dans le poste existant dans la BDD 
            * ?? '' => est-ce qu'il existe ? sinon je n'affiche rien
        -->
        <input type="text" class="form-control" name="title" id="title" value="<?= $params['post']->title ?? '' ?>">
    </div>
    <div class="form-group">
        <label for="content">Contenu de l'article</label>
        <textarea name="content" id="content" rows="8" class="form-control"><?= $params['post']->content ?? '' ?></textarea>
    </div>
    <div class="form-group">
        <label for="tags">Tags de l'article</label>
        <!-- multiple class est une liste déroulante de tags -->
        <select multiple class="form-control" id="tags" name="tags[]">
            <!-- on boucle sur les tags -->
            <!-- option va afficher un tag si à l'aide de la boucle, il en trouve un ou plusieurs -->
            <?php foreach ($params['tags'] as $tag) : ?>
                <!-- foreach -> getTags() as $postTags => surligne les tags du post choisi -->
                <!-- est-ce que notre post existe ? s'il existe alors action -->
                <option value="<?= $tag->id ?>" <?php if (isset($params['post'])) : ?> <?php foreach ($params['post']->getTags() as $postTag) {

                                                                                            echo ($tag->id === $postTag->id) ? 'selected' : '';
                                                                                        } ?> <?php endif ?>>
                    <?= $tag->name ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <!-- si le post existe alors ->enregistrer les modifs sinon -> entregistrer mon article -->
    <button type="submit" class="btn btn-primary"><?= isset($params['post']) ? "Enregistrer les modifications" : "Enregistrer mon article" ?></button>

</form>