<?php

namespace App\Models;

/** Model.php est un fichier generique dit PARENT 
 * Il contient les fonctions génériques qui vont être utilisées dans le code
 */

use PDO;
use Database\DBConnection;

abstract class Model
{
    /** la connexion se ferait que dans les classes ENFANTS donc PROTECTED ONLY */
    protected $db;
    protected $table;
    public function __construct(DBConnection $db)
    {
        /** on y stocke la connexion à la BDD */
        $this->db = $db;
    }

    /**
     * fonction all() => $this->table cherche dans $table MAIS :
     * $protected $table est nul
     * En revanche si fonction all() effectuée depuis Post.php -> elle sera égale à son contenu
     */
    public function all(): array
    {
        return $this->query("SELECT * FROM {$this->table} "); //BUG avec tag ORDER BY created_at DESC
    }
    /** fonction permettant de trouver un post par son ID */
    public function findByid(int $id): Model
    {
        /**
         * selectionne tout + besoin de recuperer dynamiquement le nom de la table où le num d'ID à la valeur de 
         * id = ? => empêche le piratage par insertion de donnée 
         */
        return $this->query("SELECT * FROM {$this->table} WHERE id = ?", [$id], true);
    }

    public function create(array $data, ?array $relations = null)
    {
        $firstParenthesis = "";
        $secondParenthesis = "";
        $i = 1;

        foreach ($data as $key => $value) {
            $comma = $i === count($data) ? "" : ", ";
            $firstParenthesis .= "{$key}{$comma}";
            $secondParenthesis .= ":{$key}{$comma}";
            $i++;
        }

        return $this->query("INSERT INTO {$this->table} ($firstParenthesis)
        VALUES($secondParenthesis)", $data);
    }
    /** UPDATE
     * $sqlRequestPart => partie de la requête SQL
     * '.=' => ajoute des keys au fur et a mesure que ça boucle
     * 'keys' ici présence le name présent dans les champs html, par ex: name='title'/'content'
     * = : précisera que key correspond bien à key
     * $i = 1 => représente la première boucle
     * $comma => correspond à une eventuelle virgule ou espace
     */
    public function update(int $id, array $data, ?array $relations = null)
    {
        $sqlRequestPart = "";
        $i = 1;

        /** $comma
         * si $i === count($data) donc est strictement égal à la valeur de fin d'itération du tableau alors fin avec '? " "' (espace)
         * sinon ": ', '" virgule en attendant la fin
         * virgule soit espace stocké dans $comma
         */
        foreach ($data as $key => $value) {
            $comma = $i === count($data) ? "" : ', ';
            $sqlRequestPart .= "{$key} = :{$key}{$comma}";
            $i++;
        }

        $data['id'] = $id;
        /** UPDATE
         * cherche à MAJ les composants de la table choisie à l'aide de ':'
         * requête préparée -> indique un param obligatoirement avec $data => tableau(array)
         */
        return $this->query("UPDATE {$this->table} SET {$sqlRequestPart} WHERE id = :id", $data);
    }
    /** requête SQL permettant que lorsque destroy() est appelée, elle est l'action de supprimer un article de la table
     * requête préparée grâce à '?' et [$id]
     */
    public function destroy(int $id): bool
    {
        return $this->query("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }
    /** fonction query()
     * elle permet d'éviter la duplication de code pour la recherche de post dans la bdd 
     * dans les fonctions all() et findById()
     * string car requete SQL
     * array NULL car
     * bool NULL -> fetchAll() | True -> fetch()
     */
    public function query(string $sql, array $param = null, bool $single = null)
    {
        // echo "</br>Model query: " . $sql;
        // var_dump( "</br>Model param: " , $param);
        /** method
         * is_null => pas d'argum en param
         * sinon ->prepare
         */
        $method = is_null($param) ? 'query' : 'prepare';
        /** strpos
         * permet de connaître la position d'une chaine de caractère
         * si c'est 'N' === 0 
         */
        if (
            strpos($sql, 'DELETE') === 0
            || strpos($sql, 'UPDATE') === 0
            || strpos($sql, 'INSERT') === 0
        ) {

            $stmt = $this->db->getPDO()->$method($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($this), [$this->db]);
            return $stmt->execute($param);
        }
        /** fetch
         * si NULL -> fetchAll()
         * sinon -> fetch
         * setFetchMode permet de récupérer la bonne table
         */
        $fetch = is_null($single) ? 'fetchAll' : 'fetch';

        $stmt = $this->db->getPDO()->$method($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($this), [$this->db]);

        /** si il y a une query -> fetch()
         * sinon (il y a un param) alors ->execute(param)-> return avec fetch()/fetchAll()
         */
        if ($method === 'query') {
            return $stmt->$fetch();
        } else {
            $stmt->execute($param);
            return $stmt->$fetch();
        }
    }
}
