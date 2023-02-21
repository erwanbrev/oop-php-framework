<?php

namespace App\Models;

/** Model.php est un fichier generique dit PARENT */

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

    public function findByid(int $id): Model
    {
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

    public function update(int $id, array $data, ?array $relations = null)
    {
        $sqlRequestPart = "";
        $i = 1;

        foreach ($data as $key => $value) {
            $comma = $i === count($data) ? "" : ', ';
            $sqlRequestPart .= "{$key} = :{$key}{$comma}";
            $i++;
        }

        $data['id'] = $id;

        return $this->query("UPDATE {$this->table} SET {$sqlRequestPart} WHERE id = :id", $data);
    }

    public function destroy(int $id): bool
    {
        return $this->query("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }

    public function query(string $sql, array $param = null, bool $single = null)
    {
        // echo "</br>Model query: " . $sql;
        // var_dump( "</br>Model param: " , $param);
        $method = is_null($param) ? 'query' : 'prepare';

        if (
            strpos($sql, 'DELETE') === 0
            || strpos($sql, 'UPDATE') === 0
            || strpos($sql, 'INSERT') === 0
        ) {

            $stmt = $this->db->getPDO()->$method($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($this), [$this->db]);
            return $stmt->execute($param);
        }

        $fetch = is_null($single) ? 'fetchAll' : 'fetch';

        $stmt = $this->db->getPDO()->$method($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($this), [$this->db]);

        if ($method === 'query') {
            return $stmt->$fetch();
        } else {
            $stmt->execute($param);
            return $stmt->$fetch();
        }
    }
}
