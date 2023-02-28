<?php

namespace App\Models;

/** Post.php => article */

use DateTime;
use App\Models\Tag;

/** class Post
 * Post.php est ici un enfant du model parent (generique) nommmé Model.php
 * Post étend ici des fonctions de Model
 * */
class Post extends Model
{
    protected $table = 'posts';

    /** getCreatedAt() -> bon formatage pour la date */
    public function getCreatedAt(): string
    {
        /** format -> defini le jour/mois/annee et heures/m = mois|i = minutes*/
        return (new DateTime($this->created_at))->format('d/m/Y à H:i');
    }
    /** montrer un extrait d'un article sur la page des articles */
    public function getExcerpt(): string
    {
        /** substr
         * quelle chaine de caractere je veux couper ? => $this->content 
         * où veut-on démarrer ? Début => 0
         * longueur de 200 caracteres disponibles
         */
        return substr($this->content, 0, 200) . '...';
    }

    public function getButton(): string
    {
        /** $this->id => récupère l'id en fonction de la création de l'article (2ème article publié = id#2) */
        return '<a href="' . HREF_ROOT . 'posts/' . $this->id . '" class="btn btn-primary">Lire l article</a>';
        /** ligne venant de la vidéo
         * return <<<HTML
         * <a href="http://localhost/oop-php-framework/posts/$this->id" class="btn btn-primary">Lire l'article</a>
         * HTML;
         */
    }

    /**
     * Summary of getTags
     * 
     * A Remplacer par  Models/Tag.php
     * @return mixed
     */
    /** getTags()
     *  fonction pour recuperer le tag lié au poste en question 
     */
    public function getTags()
    {
        //  $tag = new Tag($this->db);

        // return $tag->getPosts();
        /** jointure de table 
         * t -> alias pour tags | t.* -> recupere tout dans la table 'tags' 
         * tags t -> definition de l'alias
         * on doit lier une premiere table-> 'pivot' avec inner join
         * l'inner join si dessous signifie : 
         * post_tag avec alias 'pt' reliée avec ON ou pt.tag_id = t.id 
         */
        return $this->query("
            SELECT t.* FROM tags t
            INNER JOIN post_tag pt ON pt.tag_id = t.id
            WHERE pt.post_id = ?
        ", [$this->id]);
    }

    public function create(array $data, ?array $relations = null)
    {
        parent::create($data);

        $id = $this->db->getPDO()->lastInsertId();

        foreach ($relations as $tagId) {
            $stmt = $this->db->getPDO()->prepare("INSERT post_tag (post_id, tag_id) VALUES (?, ?)");
            $stmt->execute([$id, $tagId]);
        }

        return true;
    }

    public function update(int $id, mixed $data, ?array $relations = null)
    {
        parent::update($id, $data);

        $stmt = $this->db->getPDO()->prepare("DELETE FROM post_tag WHERE post_id = ?");
        $result = $stmt->execute([$id]);

        foreach ($relations as $tagId) {
            $stmt = $this->db->getPDO()->prepare("INSERT post_tag (post_id, tag_id) VALUES (?, ?)");
            $stmt->execute([$id, $tagId]);
        }

        if ($result) {
            return true;
        }
    }
}
