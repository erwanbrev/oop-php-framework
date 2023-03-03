<h1>Administration des articles</h1>

<?php if (isset($_GET['success'])) : ?>
    <div class="alert alert-success">Vous êtes connecté!</div>
<?php endif ?>
<!-- my-3 => margin sur l'axe des ordonnées de 3 -->
<a href="<?= HREF_ROOT ?>admin/posts/create" class="btn btn-success my-3">Créer un nouvel article</a>

<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Titre</th>
            <th scope="col">Publié le</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        <!-- boucle sur les articles -->
        <?php foreach ($params['posts'] as $post) : ?>
            <tr>
                <th scope="row"><?= $post->id ?></th>
                <td><?= $post->title ?></td>
                <td><?= $post->getCreatedAt() ?></td>
                <!-- trois boutons * un pour ajouter * un pour supprimer -->
                <td>
                    <!-- chemin vers la fonction d'édition -->
                    <a href="<?= HREF_ROOT ?>admin/posts/edit/<?= $post->id ?>" class="btn btn-warning">Modifier</a>
                    <!-- chemin vers la fonction de suppression -->
                    <form action="<?= HREF_ROOT ?>admin/posts/delete/<?= $post->id ?>" method="POST" class="d-inline">
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>