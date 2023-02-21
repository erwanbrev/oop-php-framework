<?php

namespace Database;

use PDO;

class DBConnection
{
    /** PRIVATE puisque non réutilisé en dehors de la classe DBConnection*/
    private $dbname;
    private $host;
    private $username;
    private $password;
    private $pdo;
    /** fonction constructrice initialisant le type des variables créées, en parametre */
    public function __construct(string $dbname, string $host, string $username, string $password)
    {
        /** redirection des parametres de la fonction vers les variables d'origine*/
        $this->dbname = $dbname;
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
    }
    /** fonction utilisée en dehors de la classe permettant de se connecter à la base de données avec la méthode PDO */
    public function getPDO(): PDO
    {
        /** explication du return ci-dessous :
         * retourne pdo si existant sinon s'il est NULL alors instanciation avec new PDO()
         * ?? -> est-ce qu'il est different de NULL ? si oui -> return si non -> suite 
         * [] -> tableau d'option (tableau clé=>valeur) comprenant des constantes correspondantes à des ID's permettant de piloter ce qu'on veut/pas concernant le PDO
         */
        return $this->pdo ?? $this->pdo = new PDO("mysql:dbname={$this->dbname};host={$this->host}", $this->username, $this->password, [
            /** Les différentes actions du stockage des données
             * Dans le cas d'une erreur => _ERRMODE -> mode d'erreur | *_EXCEPTION -> permet l'alerte
             * PDO récupère données par défaut -> tableau associatif => DEFAULT_FETCH_MODE
             * On veut les récupérer -> OBJ => FETCH_OBJ
             * Set les caracteres en UTF-8
             */
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET CHARACTER SET UTF8'
        ]);
    }
}
