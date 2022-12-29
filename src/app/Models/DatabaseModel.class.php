<?php

namespace kivweb\Models;

/**
 * Trida spravujici databazi.
 * @package kivweb\Models
 */
class DatabaseModel {

    /** @var DatabaseModel $database  Singleton databazoveho modelu. */
    private static $database;

    /** @var \PDO $pdo  Objekt pracujici s databazi prostrednictvim PDO. */
    private $pdo;

    /**
     * Inicializace pripojeni k databazi.
     */
    private function __construct() {
        // inicializace DB
        $this->pdo = new \PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
        // vynuceni kodovani UTF-8
        $this->pdo->exec("set names utf8");
    }

    /**
     * Tovarni metoda pro poskytnuti singletonu databazoveho modelu.
     * @return DatabaseModel    Databazovy model.
     */
    public static function getDatabaseModel(){
        if(empty(self::$database)){
            self::$database = new DatabaseModel();
        }
        return self::$database;
    }


    //////////////////////////////////////////////////////////
    ///////////  Prace s databazi  /////////////////////////
    //////////////////////////////////////////////////////////

    /**
     *  Vrati seznam vsech pohadek pro uvodni stranku.
     *  @return array Obsah uvodu.
     */
    public function getAllIntroductions():array {
        // pripravim dotaz
        $q = "SELECT * FROM ".TABLE_INTRODUCTION;
        // provedu a vysledek vratim jako pole
        // protoze je o uzkazku, tak netestuju, ze bylo neco vraceno
        return $this->pdo->query($q)->fetchAll();
    }
    
    
    /**
     *  Vrati seznam vsech uzivatelu pro spravu uzivatelu.
     *  @return array Obsah spravy uzivatelu.
     */
    public function getAllUsers():array {
        // pripravim dotaz
        $q = "SELECT * FROM ".TABLE_USER;
        // provedu a vysledek vratim jako pole
        // protoze je o uzkazku, tak netestuju, ze bylo neco vraceno
        return $this->pdo->query($q)->fetchAll();
    }
    
    /**
     *  Smaze daneho uzivatele z DB.
     *  @param int $userId  ID uzivatele.
     */
    public function deleteUser(int $userId):bool {
        // pripravim dotaz
        $q = "DELETE FROM ".TABLE_USER." WHERE id_user = $userId";
        // provedu dotaz
        $res = $this->pdo->query($q);
        // pokud neni false, tak vratim vysledek, jinak null
        if ($res) {
            // neni false
            return true;
        } else {
            // je false
            return false;
        }
    }

    //////////////////////////////////////////////////////////
    ///////////  KONEC: Prace s databazi  /////////////////////////
    //////////////////////////////////////////////////////////

}

?>
