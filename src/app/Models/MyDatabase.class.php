<?php
//////////////////////////////////////////////////////////////
////////////// Vlastni trida pro praci s databazi ////////////////
//////////////////////////////////////////////////////////////

namespace kivweb\Models;

use PDO;
use PDOException;
use PDOStatement;

/**
 * Vlastni trida spravujici databazi.
 */
class MyDatabase {
    /** @var MyDatabase $database  Singleton databazoveho modelu. */
    private static MyDatabase $database;

    /** @var PDO $pdo  PDO objekt pro praci s databazi. */
    private $pdo;

    /** @var MySession $mySession  Vlastni objekt pro spravu session. */
    private $mySession;
    /** @var string KEY_USER  Klic pro data uzivatele, ktera jsou ulozena v session. */
    private const KEY_USER = "current_user_id";

    /**
     * MyDatabase constructor.
     * Inicializace pripojeni k databazi a pokud ma byt spravovano prihlaseni uzivatele,
     * tak i vlastni objekt pro spravu session.
     * Pozn.: v samostatne praci by sprava prihlaseni uzivatele mela byt v samostatne tride.
     * Pozn.2: take je mozne do samostatne tridy vytahnout konkretni funkce pro praci s databazi.
     */
    private function __construct(){
        // inicialilzuju pripojeni k databazi - informace beru ze settings
        $this->pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $this->pdo->exec("set names utf8");
        // nastavení PDO error módu na výjimku, tj. každá chyba při práci s PDO bude výjimkou
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // inicializuju objekt pro praci se session - pouzito pro spravu prihlaseni uzivatele
        // pozn.: v samostatne praci vytvorte pro spravu prihlaseni uzivatele samostatnou tridu.
        require_once("MySessions.class.php");
        $this->mySession = new MySession();
    }

    /**
     * Tovarni metoda pro poskytnuti singletonu databazoveho modelu.
     * @return MyDatabase    Databazovy model.
     */
    public static function getMyDatabase(): MyDatabase
    {
        if(empty(self::$database)){
            self::$database = new MyDatabase();
        }
        return self::$database;
    }


    ///////////////////  Obecne funkce  ////////////////////////////////////////////

    /**
     *  Provede dotaz a bud vrati ziskana data, nebo pri chybe ji vypise a vrati null.
     *  Varianta, pokud NENI pouzit PDO::ERRMODE_EXCEPTION
     *
     *  @param string $dotaz        SQL dotaz.
     *  @return PDOStatement|null    Vysledek dotazu.
     */
    private function executeQueryWithoutException(string $dotaz){
        // vykonam dotaz
        $res = $this->pdo->query($dotaz);
        // pokud neni false, tak vratim vysledek, jinak null
        if ($res != false) {
            // neni false
            return $res;
        } else {
            // je false - vypisu prislusnou chybu a vratim null
            $error = $this->pdo->errorInfo();
            return null;
        }
    }

    /**
     *  Provede dotaz a bud vrati ziskana data, nebo pri chybe ji vypise a vrati null.
     *  Varianta, pokud je pouzit PDO::ERRMODE_EXCEPTION
     *
     *  @param string $dotaz        SQL dotaz.
     *  @return PDOStatement|null    Vysledek dotazu.
     */
    private function executeQuery(string $dotaz){
        // vykonam dotaz
        try {
            $res = $this->pdo->query($dotaz);
            return $res;
        } catch (PDOException $ex){
            echo "Nastala výjimka: ". $ex->getCode() ."<br>"
                ."Text: ". $ex->getMessage();
            return null;
        }
    }

    /**
     * Jednoduche cteni z prislusne DB tabulky.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $whereStatement    Pripadne omezeni na ziskani radek tabulky. Default "".
     * @param string $orderByStatement  Pripadne razeni ziskanych radek tabulky. Default "".
     * @return array                    Vraci pole ziskanych radek tabulky.
     */
    public function selectFromTable(string $tableName, string $whereStatement = "", string $orderByStatement = ""):array {
        // slozim dotaz
        $q = "SELECT * FROM ".$tableName
            .(($whereStatement == "") ? "" : " WHERE $whereStatement")
            .(($orderByStatement == "") ? "" : " ORDER BY $orderByStatement");
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($q);
        // pokud je null, tak vratim prazdne pole
        if($obj == null){
            return [];
        }
        // projdu jednotlive ziskane radky tabulky
        /*while($row = $vystup->fetch(PDO::FETCH_ASSOC)){
            $pole[] = $row['login'].'<br>';
        }*/
        // prevedu vsechny ziskane radky tabulky na pole
        return $obj->fetchAll();
    }

    /**
     * Cteni z databaze z nekolika tabulek
     *
     * @param array $tableNamesFields pole kde jako klic je tabulka a pote pole nazvu sloupcu tabulky co chci vybrat
     * @param array $joinFields pole kde jako klic je nazev tabulky co chci pripojit a pote sloupce na kterych se to ma propojit
     * @param string $whereStatement cast sql dotazu where
     * @param string $orderByStatement cast sql dotazu order
     * @return array vysledky vyberu z databaze
     */
    public function selectFromMultipleTablesSafe_BindNums(array $tableNamesFields, array $joinFields=null, string $whereStatement = "", array $bindValues=null, string $orderByStatement = ""):array {

        // Slozim SELECT
        $fields = array();
        foreach ($tableNamesFields as $tableName => $tableFields) {
            foreach ($tableFields as $fieldName) {
                $fields[] = $tableName . "." . $fieldName . " AS " . $tableName . "_" . $fieldName;
            }
        }
        $sql = "SELECT " . implode(", ", $fields) . " FROM " . array_keys($tableNamesFields)[0] . " ";

        // Pridam JOIN
        if ($joinFields != null) {
            foreach ($joinFields as $joinTable => $joinField) {
                $sql .= "LEFT JOIN " . $joinTable . " ON " . $joinField[0] . " = " . $joinField[1] . " ";
            }
        }


        // Pridam WHERE
        $sql .= (($whereStatement == "") ? "" : " WHERE $whereStatement");

        //Pridam ORDER
        $sql .= (($orderByStatement == "") ? "" : " ORDER BY $orderByStatement");

        //Prevence SQL Injection
        if($bindValues != null) {
            $stmt = $this->pdo->prepare($sql);
            foreach ($bindValues as $bindkey => $bind) {
                $stmt->bindValue((intval($bindkey) + 1), intval($bind), PDO::PARAM_INT);
            }

            // provedu ho a vratim vysledek
            // pokud je null, tak vratim prazdne pole
            $stmt->execute();
            $obj = $stmt->fetchAll();
            if($obj == null){
                return [];
            }

            // prevedu vsechny ziskane radky tabulky na pole
            return $obj;
        }


        //bez bind
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($sql);
        // pokud je null, tak vratim prazdne pole
        if($obj == null){
            return [];
        }

        // prevedu vsechny ziskane radky tabulky na pole
        return $obj->fetchAll();

    }

    /**
     * Cteni z databaze z nekolika tabulek
     *
     * @param array $tableNamesFields pole kde jako klic je tabulka a pote pole nazvu sloupcu tabulky co chci vybrat
     * @param array $joinFields pole kde jako klic je nazev tabulky co chci pripojit a pote sloupce na kterych se to ma propojit
     * @param string $whereStatement cast sql dotazu where
     * @param string $orderByStatement cast sql dotazu order
     * @return array vysledky vyberu z databaze
     */
    public function selectFromMultipleTablesSafe_BindString(array $tableNamesFields, array $joinFields=null, string $whereStatement = "", array $bindValues=null, string $orderByStatement = ""):array {

        // Slozim SELECT
        $fields = array();
        foreach ($tableNamesFields as $tableName => $tableFields) {
            foreach ($tableFields as $fieldName) {
                $fields[] = $tableName . "." . $fieldName . " AS " . $tableName . "_" . $fieldName;
            }
        }
        $sql = "SELECT " . implode(", ", $fields) . " FROM " . array_keys($tableNamesFields)[0] . " ";

        // Pridam JOIN
        if ($joinFields != null) {
            foreach ($joinFields as $joinTable => $joinField) {
                $sql .= "LEFT JOIN " . $joinTable . " ON " . $joinField[0] . " = " . $joinField[1] . " ";
            }
        }


        // Pridam WHERE
        $sql .= (($whereStatement == "") ? "" : " WHERE $whereStatement");

        //Pridam ORDER
        $sql .= (($orderByStatement == "") ? "" : " ORDER BY $orderByStatement");

        //Prevence SQL Injection
        if($bindValues != null) {
            $stmt = $this->pdo->prepare($sql);
            foreach ($bindValues as $bindkey => $bind) {
                $stmt->bindValue((intval($bindkey) + 1), $bind, PDO::PARAM_STR);
            }

            // provedu ho a vratim vysledek
            // pokud je null, tak vratim prazdne pole
            $stmt->execute();
            $obj = $stmt->fetchAll();
            if($obj == null){
                return [];
            }

            // prevedu vsechny ziskane radky tabulky na pole
            return $obj;
        }


        //bez bind
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($sql);
        // pokud je null, tak vratim prazdne pole
        if($obj == null){
            return [];
        }

        // prevedu vsechny ziskane radky tabulky na pole
        return $obj->fetchAll();

    }
    /**
     * Vklada data do databaze
     *
     * @param array $tableNamesFields pole kde jako klic je tabulka a pote pole nazvu sloupcu tabulky co chci vybrat
     *
     * @return array vysledky vyberu z databaze
     */
    public function insertSafe_BindString(string $table, array $columnValues):bool {

        // Slozim SELECT
        $columns = array();
        $values = array();
        $valuesPlaceholder = array();
        foreach ($columnValues as $column => $value) {
            $columns[] = $table . "." . $column;
            $values[] = $value;
            $valuesPlaceholder[] = "?";
        }
        $sql = "INSERT INTO ".$table." ( " . implode(", ", $columns) . " )".
        " VALUES ( ". implode(", ", $valuesPlaceholder) . " ) ";


        //Prevence SQL Injection
        if($values != null) {
            $stmt = $this->pdo->prepare($sql);
            foreach ($values as $bindkey => $bind) {
                $stmt->bindValue(($bindkey + 1), $bind, PDO::PARAM_STR);
            }

            // provedu ho a vratim vysledek
            // pokud je null, tak vratim prazdne pole
            $obj = $stmt->execute();
            if($obj == null){
                return false;
            }

            // prevedu vsechny ziskane radky tabulky na pole
            return true;
        }


        //bez bind
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($sql);
        // pokud je null, tak vratim prazdne pole
        if($obj == null){
            return false;
        }

        // prevedu vsechny ziskane radky tabulky na pole
        return true;

    }

    /**
     * Aktualizuje hodnoty v databazi
     *
     * @param array $tableNamesFields pole kde jako klic je tabulka a pote pole nazvu sloupcu tabulky co chci vybrat
     *
     * @return array vysledky vyberu z databaze
     */
    public function updateSafe_BindString(string $table, array $columnNames, string $whereStatement = "", array $bindValues=null):bool {

        // Slozim SELECT
        $columns_placeholders = array();
        foreach ($columnNames as $column) {
            $columns_placeholders[] = " " . $column . " = ? ";
        }
        $sql = "UPDATE ".$table." SET " . implode(", ", $columns_placeholders) . " ";

        // Pridam WHERE
        $sql .= (($whereStatement == "") ? "" : " WHERE $whereStatement");


        //Prevence SQL Injection
        if($bindValues != null) {
            $stmt = $this->pdo->prepare($sql);
            foreach ($bindValues as $bindkey => $bind) {
                $stmt->bindValue(($bindkey + 1), $bind, PDO::PARAM_STR);
            }

            // provedu ho a vratim vysledek
            // pokud je null, tak vratim prazdne pole
            $obj = $stmt->execute();
            echo $sql;

            // prevedu vsechny ziskane radky tabulky na pole
            return true;
        }


        //bez bind
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($sql);
        // pokud je null, tak vratim prazdne pole


        // prevedu vsechny ziskane radky tabulky na pole
        return true;

    }

    /**
     * Cteni z databaze z nekolika tabulek
     *
     * @param array $tableNamesFields pole kde jako klic je tabulka a pote pole nazvu sloupcu tabulky co chci vybrat
     * @param array $joinFields pole kde jako klic je nazev tabulky co chci pripojit a pote sloupce na kterych se to ma propojit
     * @param string $whereStatement cast sql dotazu where
     * @param string $orderByStatement cast sql dotazu order
     * @return array vysledky vyberu z databaze
     */
    public function selectFromMultipleTablesSafeOld(array $tableNamesFields, array $joinFields=null, string $whereStatement = "", string $orderByStatement = ""):array {

        $bind_values = array();
        $bind_values_id = 0;

        // Slozim SELECT
        $fields = array();
        foreach ($tableNamesFields as $tableName => $tableFields) {
            foreach ($tableFields as $fieldName) {
                $fieldSql = ":".$bind_values_id . " AS ";
                $bind_values[$bind_values_id++] = $tableName.".".$fieldName;

                $fieldSql .= ":".$bind_values_id;
                $bind_values[$bind_values_id++] = $tableName."_".$fieldName;

                $fields[] =  $fieldSql;
            }
        }
        $sql = "SELECT " . implode(", ", $fields) . " FROM " . ":".$bind_values_id . " ";
        $bind_values[$bind_values_id++] = array_keys($tableNamesFields)[0];

        // Pridam JOIN
        if ($joinFields != null) {
            foreach ($joinFields as $joinTable => $joinField) {
                $sql .= "JOIN " . ":".$bind_values_id . " ON ";
                $bind_values[$bind_values_id++] = $joinTable;

                $sql .= ":".$bind_values_id . " = ";
                $bind_values[$bind_values_id++] = $joinField[0];

                $sql .= ":".$bind_values_id . " ";
                $bind_values[$bind_values_id++] = $joinField[1];
            }
        }

        // Pridam WHERE
        if ($whereStatement != ""){
            $sql .= " WHERE " . ":".$bind_values_id;
            $bind_values[$bind_values_id++] = $whereStatement;
        }

        //Pridam ORDER
        if ($orderByStatement != ""){
            $sql .= " ORDER BY " . ":".$bind_values_id;
            $bind_values[$bind_values_id++] = $orderByStatement;
        }




        //Prevence SQL Injection
        $stmt = $this->pdo->prepare($sql);
        foreach ($bind_values as $bindkey => $bind){
            $stmt->bindValue(":".$bindkey, $bind);

        }

        // provedu ho a vratim vysledek
        $obj = $stmt->execute();
        // pokud je null, tak vratim prazdne pole
        if($obj == null){
            return [];
        }

        // prevedu vsechny ziskane radky tabulky na pole
        return $obj->fetchAll();
    }

    /**
     * Cteni z databaze z nekolika tabulek
     *
     * @param array $tableNamesFields pole kde jako klic je tabulka a pote pole nazvu sloupcu tabulky co chci vybrat
     * @param array $joinFields pole kde jako klic je nazev tabulky co chci pripojit a pote sloupce na kterych se to ma propojit
     * @param string $whereStatement cast sql dotazu where
     * @param string $orderByStatement cast sql dotazu order
     * @return array vysledky vyberu z databaze
     */
    public function selectFromMultipleTables(array $tableNamesFields, array $joinFields=null, string $whereStatement = "", string $orderByStatement = ""):array {

        // Slozim SELECT
        $fields = array();
        foreach ($tableNamesFields as $tableName => $tableFields) {
            foreach ($tableFields as $fieldName) {
                $fields[] = $tableName . "." . $fieldName . " AS " . $tableName . "_" . $fieldName;
            }
        }
        $sql = "SELECT " . implode(", ", $fields) . " FROM " . array_keys($tableNamesFields)[0] . " ";

        // Pridam JOIN
        if ($joinFields != null) {
            foreach ($joinFields as $joinTable => $joinField) {
                $sql .= "LEFT JOIN " . $joinTable . " ON " . $joinField[0] . " = " . $joinField[1] . " ";
            }
        }

        // Pridam WHERE
        $sql .= (($whereStatement == "") ? "" : " WHERE $whereStatement");

        //Pridam ORDER
        $sql .= (($orderByStatement == "") ? "" : " ORDER BY $orderByStatement");



        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($sql);
        // pokud je null, tak vratim prazdne pole
        if($obj == null){
            return [];
        }

        // prevedu vsechny ziskane radky tabulky na pole
        return $obj->fetchAll();
    }


    /**
     * Jednoduche vlozeni do prislusne tabulky.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $insertStatement   Text s nazvy sloupcu pro insert.
     * @param string $insertValues      Text s hodnotami pro prislusne sloupce.
     * @return bool                     Vlozeno v poradku?
     */
    public function insertIntoTable(string $tableName, string $insertStatement, string $insertValues):bool {
        // slozim dotaz
        $q = "INSERT INTO $tableName($insertStatement) VALUES ($insertValues)";
        // provedu ho a vratim uspesnost vlozeni
        $obj = $this->executeQuery($q);
        // pokud ($obj == null), tak vratim false
        return ($obj != null);
    }

    /**
     * Jednoducha uprava radku databazove tabulky.
     *
     * @param string $tableName                     Nazev tabulky.
     * @param string $updateStatementWithValues     Cela cast updatu s hodnotami.
     * @param string $whereStatement                Cela cast pro WHERE.
     * @return bool                                 Upraveno v poradku?
     */
    public function updateInTable(string $tableName, string $updateStatementWithValues, string $whereStatement):bool {
        // slozim dotaz
        $q = "UPDATE $tableName SET $updateStatementWithValues WHERE $whereStatement";
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($q);
        // pokud ($obj == null), tak vratim false
        return ($obj != null);
    }

    /**
     * Dle zadane podminky maze radky v prislusne tabulce.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $whereStatement    Podminka mazani.
     * @return bool
     */
    public function deleteFromTable(string $tableName, string $whereStatement):bool {
        // slozim dotaz
        $q = "DELETE FROM $tableName WHERE $whereStatement";
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($q);
        // pokud ($obj == null), tak vratim false
        return ($obj != null);
    }

    ///////////////////  KONEC: Obecne funkce  ////////////////////////////////////////////

    ///////////////////  Konkretni funkce  ////////////////////////////////////////////

    /**
     * Ziskani zaznamu o jednom prispevku podle id.
     *
     * @return array    Pole se info prispevku.
     */
    public function getContributionInfobyId($id): array
    {

        $tableNamesFields = array(
            DB_TABLES['contributions']  => array(
                "id_contributions",
                "name",
                "abstract",
                "state",
                "created",
            ),
            DB_TABLES['users']  => array(
                "name",
                "surname"
            ),
        );

        $joinFields = array(
            DB_TABLES['users'] => array(
                DB_TABLES['contributions'].".id_users",
                DB_TABLES['users'].".id_users"
            ),
        );

        $whereStatement = DB_TABLES['contributions'].".id_contributions = ? AND ". DB_TABLES['contributions'].".deleted='0'";
        $bindValues = array(
            $id
        );

        $contributions = $this->selectFromMultipleTablesSafe_BindNums(
            $tableNamesFields, $joinFields, $whereStatement, $bindValues, DB_TABLES['contributions'].".created DESC");

        return $contributions;
    }

    /**
     * Ziskani soubory o jednom prispevku podle id.
     *
     * @return array    Pole se souborami prispevku.
     */
    public function getContributionFilesbyIdcontribution($id_contribution): array
    {

        $tableNamesFields = array(
            DB_TABLES['contributions_files']  => array(
                "id_contributions_files",
                "name",
                "file"
            ),
        );

        $whereStatement = DB_TABLES['contributions_files'].".id_contributions = ? AND ". DB_TABLES['contributions_files'].".deleted='0'";
        $bindValues = array(
            $id_contribution
        );
        $contributions = $this->selectFromMultipleTablesSafe_BindNums(
            $tableNamesFields, null, $whereStatement,$bindValues, DB_TABLES['contributions_files'].".created DESC");

        return $contributions;
    }

    /**
     * Ziskani soubory o jednom prispevku podle id.
     *
     * @return array    Pole se souborami prispevku.
     */
    public function getContributionFilebyId($id): array
    {

        $tableNamesFields = array(
            DB_TABLES['contributions_files']  => array(
                "file",
                "name",
            ),
        );

        $whereStatement = DB_TABLES['contributions_files'].".id_contributions_files = ? AND ". DB_TABLES['contributions_files'].".deleted='0'";
        $bindValues = array(
          $id
        );
        $contributions = $this->selectFromMultipleTablesSafe_BindNums(
            $tableNamesFields, null, $whereStatement, $bindValues, DB_TABLES['contributions_files'].".created DESC");

        return $contributions;
    }

    /**
     * Ziskani recenzi o jednom prispevku podle id.
     *
     * @return array    Pole se recenzemi.
     */
    public function getReviewsbyIdContribution($id_contributions): array{

        $tableNamesFields = array(
            DB_TABLES['reviews']  => array(
                "id_reviews",
                "id_users",
                "abstract_review",
                "topic_review",
                "author_review",
                "comment"
            ),
            DB_TABLES['users']  => array(
                "name",
                "surname",
            ),
        );

        $joinFields = array(
            DB_TABLES['users'] => array(
                DB_TABLES['reviews'].".id_users",
                DB_TABLES['users'].".id_users"
            )
        );

        $whereStatement = DB_TABLES['reviews'].".id_contributions = ? AND ". DB_TABLES['reviews'].".deleted = '0'";
        $bindValues = array(
            $id_contributions
        );
        $contributions = $this->selectFromMultipleTablesSafe_BindNums(
            $tableNamesFields, $joinFields, $whereStatement,$bindValues, DB_TABLES['reviews'].".created DESC");

        return $contributions;

    }

    /**
     * Ziskani prispevku podle statusu
     *
     * @return array    Pole se prispevku.
     */
    public function getContributionsByState($state): array{

        $tableNamesFields = array(
            DB_TABLES['contributions']  => array(
                "id_contributions",
                "name",
                "abstract",
                "state",
                "created",
            ),
            DB_TABLES['users']  => array(
                "name",
                "surname"
            ),
        );

        $joinFields = array(
            DB_TABLES['users'] => array(
                DB_TABLES['contributions'].".id_users",
                DB_TABLES['users'].".id_users"
            )
        );

        $whereStatement = DB_TABLES['contributions'].".state = ?  AND ". DB_TABLES['contributions'].".deleted='0'";

        $bindValues = array(
            $state
        );

        $orderStatement = DB_TABLES['contributions'].".created DESC";

        $contributions = $this->selectFromMultipleTablesSafe_BindNums(
            $tableNamesFields, $joinFields, $whereStatement,$bindValues, $orderStatement);

        return $contributions;

    }
    public function getAllContributions(): array{

        $tableNamesFields = array(
            DB_TABLES['contributions']  => array(
                "id_contributions",
            )
        );


        $whereStatement = DB_TABLES['contributions'].".deleted='0'";


        $orderStatement = DB_TABLES['contributions'].".created DESC";

        $contributions = $this->selectFromMultipleTablesSafe_BindNums(
            $tableNamesFields, null, $whereStatement,null, $orderStatement);

        return $contributions;

    }

    /**
     * Ziskani prispevku podle statusu
     *
     * @return array    Pole se prispevku.
     */
    public function getContributionsByStateIduser($state, $id_users): array{

        $tableNamesFields = array(
            DB_TABLES['contributions']  => array(
                "id_contributions",
                "name",
                "abstract",
                "state",
                "created",
            ),
            DB_TABLES['users']  => array(
                "name",
                "surname"
            ),
        );

        $joinFields = array(
            DB_TABLES['users'] => array(
                DB_TABLES['contributions'].".id_users",
                DB_TABLES['users'].".id_users"
            )
        );

        $whereStatement = DB_TABLES['contributions'].".state = ?  AND ". DB_TABLES['contributions'].".deleted='0' AND ".
            DB_TABLES['contributions'].".id_users = ? ";

        $bindValues = array(
            $state,
            $id_users
        );

        $orderStatement = DB_TABLES['contributions'].".created DESC";

        $contributions = $this->selectFromMultipleTablesSafe_BindNums(
            $tableNamesFields, $joinFields, $whereStatement,$bindValues, $orderStatement);

        return $contributions;

    }

    /**
     * Ziskani Uzivatele podle id.
     *
     * @return array    Pole se informacemi o uzivateli
     */
    public function getUserbyId($id): array{

        $tableNamesFields = array(
            DB_TABLES['users']  => array(
                "username",
                "name",
                "surname",
                "email",
                "role",
                "blocked"
            ),
        );

        $whereStatement = DB_TABLES['users'].".id_users = ? AND ". DB_TABLES['users'].".deleted = '0' AND ". DB_TABLES['users'].".blocked = '0'";
        $bindValues = array(
            $id
        );

        $orderStatement = DB_TABLES['users'].".created DESC";
        $contributions = $this->selectFromMultipleTablesSafe_BindNums(
            $tableNamesFields, null, $whereStatement, $bindValues, $orderStatement);

        return $contributions;
    }

    /**
     * Ziskani Uzivatele podle id i zablokovane
     *
     * @return array    Pole se informacemi o uzivateli
     */
    public function getUserbyId_Block($id): array{

        $tableNamesFields = array(
            DB_TABLES['users']  => array(
                "username",
                "name",
                "surname",
                "email",
                "role",
                "blocked"
            ),
        );

        $whereStatement = DB_TABLES['users'].".id_users = ? AND ". DB_TABLES['users'].".deleted = '0' ";
        $bindValues = array(
            $id
        );

        $orderStatement = DB_TABLES['users'].".created DESC";
        $contributions = $this->selectFromMultipleTablesSafe_BindNums(
            $tableNamesFields, null, $whereStatement, $bindValues, $orderStatement);

        return $contributions;
    }

    /**
     * Ziskani Uzivatele podle kteri jsou zablokovani
     *
     * @return array    Pole se informacemi o uzivateli
     */
    public function getUsersBlocked(): array{

        $tableNamesFields = array(
            DB_TABLES['users']  => array(
                "id_users",
                "username",
                "name",
                "surname",
                "email",
            ),
        );

        $whereStatement = DB_TABLES['users'].".deleted = '0' AND ". DB_TABLES['users'].".blocked = '1'";

        $orderStatement = DB_TABLES['users'].".created DESC";
        $contributions = $this->selectFromMultipleTablesSafe_BindNums(
            $tableNamesFields, null, $whereStatement, null, $orderStatement);

        return $contributions;
    }

    /**
     * Ziskani prideleni recenzi podle statusu
     *
     * @return array    Pole se prispevku.
     */
    public function getReviewAssignByState($state): array{

        $tableNamesFields = array(
            DB_TABLES['reviews_assignments']  => array(
                "id_contributions",
            ),
        );

        $whereStatement = DB_TABLES['reviews_assignments'].".state = ?  AND ". DB_TABLES['reviews_assignments'].".deleted='0'";

        $bindValues = array(
            $state
        );

        $orderStatement = DB_TABLES['reviews_assignments'].".created DESC";

        $contributions = $this->selectFromMultipleTablesSafe_BindNums(
            $tableNamesFields, null, $whereStatement,$bindValues, $orderStatement);

        return $contributions;

    }

    /**
     * Ziskani prispevku podle statusu
     * a jestli byl pridelen recenzentovi
     *
     * @return array    Pole se prispevku.
     */
    public function getReviewContributionsbyIdReviewer($id_reviewer, $contributions_state, $reviewAssi_state): array{

        $tableNamesFields = array(
            DB_TABLES['contributions']  => array(
                "id_contributions",
                "name",
                "abstract",
                "state",
                "created",
            ),
            DB_TABLES['users']  => array(
                "name",
                "surname"
            ),
        );

        $joinFields = array(
            DB_TABLES['users'] => array(
                DB_TABLES['contributions'].".id_users",
                DB_TABLES['users'].".id_users"
            )
        );

        $inwhereStatement = DB_TABLES['reviews_assignments'].".id_users = ? AND ".
            DB_TABLES['reviews_assignments'].".deleted = 0 AND ".
            DB_TABLES['reviews_assignments'].".state = ? ";

        $instatement = " SELECT ". DB_TABLES['reviews_assignments']. ".id_contributions FROM ". DB_TABLES['reviews_assignments']. " WHERE " . $inwhereStatement;

        $whereStatement = DB_TABLES['contributions'].".id_contributions IN ( ".$instatement." ) AND ".
            DB_TABLES['contributions'].".state = ? ";



        $bindValues = array(
            $id_reviewer,
            $reviewAssi_state,
            $contributions_state
        );

        $orderStatement = DB_TABLES['contributions'].".created DESC";

        $contributions = $this->selectFromMultipleTablesSafe_BindNums(
            $tableNamesFields, $joinFields, $whereStatement,$bindValues, $orderStatement);

        return $contributions;

    }

    /**
     * Ziskani uzivatelu podle role
     *
     * @return array    Pole se uzivateli.
     */
    public function getUsersbyRole($role): array{

        $tableNamesFields = array(
            DB_TABLES['users']  => array(
                "id_users",
                "username",
                "name",
                "surname",
                "email",
            ),
        );

        $whereStatement = DB_TABLES['users'].".role = ?  AND ". DB_TABLES['users'].".deleted='0' AND ".
            DB_TABLES['users'].".blocked='0'";

        $bindValues = array(
            $role
        );

        $orderStatement = DB_TABLES['users'].".created DESC";

        $contributions = $this->selectFromMultipleTablesSafe_BindNums(
            $tableNamesFields, null, $whereStatement,$bindValues, $orderStatement);

        return $contributions;

    }

    /**
     * Ziskani recenzentu pridelenych
     * recenzovat prispevek
     *
     * @return array    Pole se uzivateli.
     */
    public function getAssignedReviewersbyIdcontributions($id_contribution): array{

        $tableNamesFields = array(
            DB_TABLES['reviews_assignments']  => array(
                "id_users",
            ),
        );

        $whereStatement = DB_TABLES['reviews_assignments'].".id_contributions = ?  AND ". DB_TABLES['reviews_assignments'].".deleted='0'";

        $bindValues = array(
            $id_contribution
        );

        $orderStatement = DB_TABLES['reviews_assignments'].".created DESC";

        $contributions = $this->selectFromMultipleTablesSafe_BindNums(
            $tableNamesFields, null, $whereStatement,$bindValues, $orderStatement);

        return $contributions;

    }

    /**
     * Vytvoreni noveho uzivatele v databazi.
     *
     * @param string $login     Login.
     * @param string $jmeno     Jmeno.
     * @param string $email     E-mail.
     * @param int $idPravo      Je cizim klicem do tabulky s pravy.
     * @return bool             Vlozen v poradku?
     */
    public function addNewUser(string $username, string $name, string $surname, string $email, string $password){
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        $insert = array(
            'username' => $username,
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'password' => $password_hashed
        );
        return $this->insertSafe_BindString(DB_TABLES['users'], $insert);
    }

    /**
     * Vytvoreni noveho prispevku v databazi.
     *
     * @param string $login     Login.
     * @param string $jmeno     Jmeno.
     * @param string $email     E-mail.
     * @param int $idPravo      Je cizim klicem do tabulky s pravy.
     * @return bool             Vlozen v poradku?
     */
    public function addContribution(string $name=null, string $abstract=null,string $filename=null, $filecontent=null){
        $userid = $this->getLoggedUserId();

        $insert = array(
            'name' => $name,
            'abstract' => $abstract,
            'id_users' => $userid
        );
        $isinserted = $this->insertSafe_BindString(DB_TABLES['contributions'], $insert);

        if($isinserted){
            $contribution = $this->getAllContributions();
            if ($contribution!=null AND $filename != null AND $filecontent!=null){
                $lastcontribution = $contribution[0];
                $this->addContributionFile($lastcontribution['contributions_id_contributions'], $filename, $filecontent);
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * Vytvoreni noveho prispevku v databazi.
     *
     * @param string $login     Login.
     * @param string $jmeno     Jmeno.
     * @param string $email     E-mail.
     * @param int $idPravo      Je cizim klicem do tabulky s pravy.
     * @return bool             Vlozen v poradku?
     */
    public function addReview($id_review, $id_contributions,$topic_review, $author_review, $abstract_review, $comment_review){
        echo "ADD REVIEW";
        $userid = $this->getLoggedUserId();

        $insert = array(
            'topic_review' => $topic_review,
            'author_review' => $author_review,
            'abstract_review' => $abstract_review,
            'comment' => $comment_review,
            'id_users' => $userid,
            'id_contributions' => $id_contributions
        );

        $isinserted = $this->insertSafe_BindString(DB_TABLES['reviews'], $insert);

        //REVIEW ASSIGNEMENT
        $columns = array(
            'state',
        );
        $bindValues = array(
            0,
            $id_review,
            $id_contributions
        );
        $whereStatement = DB_TABLES['reviews_assignments'].".id_users = ? AND ".
            DB_TABLES['reviews_assignments'].".id_contributions = ? ";

        $this->updateSafe_BindString(DB_TABLES['reviews_assignments'], $columns, $whereStatement, $bindValues);

        if($isinserted){
            return true;
        }
        return false;
    }
    /**
     * Aktualizace prispevku v databazi
     *
     * @param string $login     Login.
     * @param string $jmeno     Jmeno.
     * @param string $email     E-mail.
     * @param int $idPravo      Je cizim klicem do tabulky s pravy.
     * @return bool             Vlozen v poradku?
     */
    public function updateUser($id_user ,string $username, string $name, string $surname, string $email, string $password=null){

        $columns = array(
            'username',
            'name',
            'surname',
            'email',
        );
        if($password!=null){
            $columns[] = 'password';
        }

        $bindValues = array(
            $username,
            $name,
            $surname,
            $email,
        );
        if($password!=null){
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $bindValues[] = $password_hashed;
        }
        $bindValues[] = $id_user;

        $whereStatement = DB_TABLES['users'].".id_users = ? ";

        $isupdated = $this->updateSafe_BindString(DB_TABLES['users'], $columns, $whereStatement, $bindValues);

        return $isupdated;
    }

    /**
     * Aktualizace prispevku v databazi
     *
     * @param string $login     Login.
     * @param string $jmeno     Jmeno.
     * @param string $email     E-mail.
     * @param int $idPravo      Je cizim klicem do tabulky s pravy.
     * @return bool             Vlozen v poradku?
     */
    public function updateContribution($id_contribution , string $name=null, string $abstract=null, string $filename=null, $filecontent=null){
        $userid = $this->getLoggedUserId();

        $columns = array(
            'name',
            'abstract',
        );
        $bindValues = array(
            $name,
            $abstract,
            $id_contribution
        );
        $whereStatement = DB_TABLES['contributions'].".id_contributions = ? ";

        $isupdated = $this->updateSafe_BindString(DB_TABLES['contributions'], $columns, $whereStatement, $bindValues);

        if($filename != null OR $filecontent !=null){
            $this->addContributionFile($id_contribution, $filename, $filecontent);
            return true;
        }
        return false;
    }


    /**
     * Vytvoreni noveho prispevku v databazi.
     *
     * @param string $login     Login.
     * @param string $jmeno     Jmeno.
     * @param string $email     E-mail.
     * @param int $idPravo      Je cizim klicem do tabulky s pravy.
     * @return bool             Vlozen v poradku?
     */
    public function addContributionFile(string $id_contribution, string  $filename, $filecontent){

        $insert = array(
            'id_contributions' => $id_contribution,
            'name' => $filename,
            'file' => $filecontent
        );
        return $this->insertSafe_BindString(DB_TABLES['contributions_files'], $insert);
    }

    /**
     * Oznaceni soboru prispevku ze je smazan
     *
     */
    public function deleteContributionFilebyId($id){

        $columns = array(
            'deleted'
        );

        $bindValues = array(
            '1',
            $id
        );

        $wherestatement = DB_TABLES['contributions_files'] . ".id_contributions_files = ? ";

        return $this->updateSafe_BindString(DB_TABLES['contributions_files'], $columns, $wherestatement, $bindValues);
    }

    /**
     * Oznaceni soboru prispevku ze je smazan
     *
     */
    public function deleteContributionbyId($id){

        $columns = array(
            'deleted'
        );

        $bindValues = array(
            '1',
            $id
        );

        $wherestatement = DB_TABLES['contributions'] . ".id_contributions = ? ";
        $return_str = $this->updateSafe_BindString(DB_TABLES['contributions'], $columns, $wherestatement, $bindValues);

        return $return_str;
    }

    /**
     * Oznaceni soboru prispevku ze je smazan
     *
     */
    public function deleteReviewbyId($id, $id_contributions){

        $columns = array(
            'deleted'
        );

        $bindValues = array(
            '1',
            $id
        );

        $wherestatement = DB_TABLES['reviews'] . ".id_reviews = ? ";
        $return_str = $this->updateSafe_BindString(DB_TABLES['reviews'], $columns, $wherestatement, $bindValues);

        //REVIEW ASSIGNEMENT
        $columns = array(
            'state',
        );
        $bindValues = array(
            0,
            $id,
            $id_contributions
        );
        $whereStatement = DB_TABLES['reviews_assignments'].".id_users = ? AND ".
            DB_TABLES['reviews_assignments'].".id_contributions = ? ";

        return $return_str;
    }

    ///////////////////  KONEC: Konkretni funkce  ////////////////////////////////////////////

    ///////////////////  Sprava prihlaseni uzivatele  ////////////////////////////////////////

    /**
     * Overi, zda muse byt uzivatel prihlasen a pripadne ho prihlasi.
     *
     * @param string $login     Login uzivatele.
     * @param string $password     Heslo uzivatele.
     * @return bool             Byl prihlasen?
     */
    public function userLogin(string $login, string $password):bool {


        $tableNamesFields = array(
            DB_TABLES['users']  => array(
                "id_users",
                "password"
            ),
        );

        $whereStatement = DB_TABLES['users'].".username = ? AND "
            . DB_TABLES['users'].".deleted='0' AND ".
            DB_TABLES['users'].".blocked='0'";

        $bindValues = array(
            $login
        );

        $orderStatement = DB_TABLES['users'].".created DESC";

        $users = $this->selectFromMultipleTablesSafe_BindString(
            $tableNamesFields, null, $whereStatement,$bindValues, $orderStatement);

        // ziskal jsem uzivatele
        if(count($users)==1){
            if (password_verify($password, $users[0]['users_password'])) {
                // ziskal - ulozim ID prvniho nalezeneho uzivatele do session
                $this->mySession->addSession(self::KEY_USER, $users[0]['users_id_users']);
                return true;
            }
            return false;
        }
        // neziskal jsem uzivatele
        return false;
    }

    public function checkUserLogin(string $login, string $password):bool {


        $tableNamesFields = array(
            DB_TABLES['users']  => array(
                "id_users",
                "password"
            ),
        );

        $whereStatement = DB_TABLES['users'].".username = ? AND "
            . DB_TABLES['users'].".deleted='0' AND ".
            DB_TABLES['users'].".blocked='0'";

        $bindValues = array(
            $login
        );

        $orderStatement = DB_TABLES['users'].".created DESC";

        $users = $this->selectFromMultipleTablesSafe_BindString(
            $tableNamesFields, null, $whereStatement,$bindValues, $orderStatement);

        // ziskal jsem uzivatele
        if(count($users)==1){
            if (password_verify($password, $users[0]['users_password'])) {
                // ziskal - ulozim ID prvniho nalezeneho uzivatele do session
               // $this->mySession->addSession(self::KEY_USER, $users[0]['users_id_users']);
                return true;
            }

            return false;
        }

        // neziskal jsem uzivatele
        return false;
    }

    /**
     * Odhlasi soucasneho uzivatele.
     */
    public function userLogout(){
        $this->mySession->removeSession(self::KEY_USER);
        header('Location: index.php?page=intro');
    }

    /**
     * Test, zda je nyni uzivatel prihlasen.
     *
     * @return bool     Je prihlasen?
     */
    public function isUserLogged():bool {
        return $this->mySession->isSessionSet(self::KEY_USER);
    }

    /**
     * Pokud je uzivatel prihlasen, tak vrati jeho data,
     * ale pokud nebyla v session nalezena, tak vypise chybu.
     *
     * @return mixed|null   Data uzivatele nebo null.
     */
    public function getLoggedUserData(){
        if($this->isUserLogged()){
            // ziskam data uzivatele ze session
            $userId = $this->mySession->readSession(self::KEY_USER);
            $error_text = "Data%20přihlášeného%20uživatele%20nebyla%20nalezena,%20a%20proto%20byl%20uživatel%20odhlášen.";
            // pokud nemam data uzivatele, tak vypisu chybu a vynutim odhlaseni uzivatele
            if($userId == null) {
                // nemam data uzivatele ze session - vypisu jen chybu, uzivatele odhlasim a vratim null
                header('Location: index.php?page=error&error_text='.$error_text);
                $this->userLogout();
                // vracim null
                return null;
            }
            // nactu data uzivatele z databaze
            $userData = $this->getUserbyId($userId);
            // mam data uzivatele?
            if(empty($userData)){
                // nemam - vypisu jen chybu, uzivatele odhlasim a vratim null
                header('Location: index.php?page=error&error_text='.$error_text);
                $this->userLogout();
                return null;
            }
            // protoze DB vraci pole uzivatelu, tak vyjmu jeho prvni polozku a vratim ziskana data uzivatele
            return $userData;
        }
        // uzivatel neni prihlasen - vracim null
        return null;
    }

    /**
     * Pokud je uzivatel prihlasen, tak vrati jeho id,
     * ale pokud nebyla v session nalezena, tak vypise chybu.
     *
     * @return id uzivatele
     */
    public function getLoggedUserId(){
        if($this->isUserLogged()){
            // ziskam data uzivatele ze session
            $userId = $this->mySession->readSession(self::KEY_USER);
            $error_text = "Data%20přihlášeného%20uživatele%20nebyla%20nalezena,%20a%20proto%20byl%20uživatel%20odhlášen.";
            // pokud nemam data uzivatele, tak vypisu chybu a vynutim odhlaseni uzivatele
            if($userId == null) {
                // nemam data uzivatele ze session - vypisu jen chybu, uzivatele odhlasim a vratim null
                header('Location: index.php?page=error&error_text='.$error_text);
                $this->userLogout();
                // vracim null
                return null;
            }

            // protoze DB vraci pole uzivatelu, tak vyjmu jeho prvni polozku a vratim ziskana data uzivatele
            return $userId;
        }
        // uzivatel neni prihlasen - vracim null
        return null;
    }

    /**
     * Pokud je uzivatel prihlasen, tak vrati jeho roli,
     * ale pokud nebyla v session nalezena, tak vypise chybu.
     *
     * @return mixed|null  cislo role nebo null.
     */
    public function getLoggedUserRole(){
        if($this->isUserLogged()){
            // ziskam data uzivatele ze session
            $userId = $this->mySession->readSession(self::KEY_USER);
            $error_text = "Data%20přihlášeného%20uživatele%20nebyla%20nalezena,%20a%20proto%20byl%20uživatel%20odhlášen.";
            // pokud nemam data uzivatele, tak vypisu chybu a vynutim odhlaseni uzivatele
            if($userId == null) {
                // nemam data uzivatele ze session - vypisu jen chybu, uzivatele odhlasim a vratim null
                header('Location: index.php?page=error&error_text='.$error_text);
                $this->userLogout();
                // vracim null
                return null;
            }
            // nactu data uzivatele z databaze
            $userData = $this->getUserbyId($userId);
            // mam data uzivatele?
            if(empty($userData)){
                // nemam - vypisu jen chybu, uzivatele odhlasim a vratim null
                header('Location: index.php?page=error&error_text='.$error_text);
                $this->userLogout();
                return null;
            }
            // protoze DB vraci pole uzivatelu, tak vyjmu jeho prvni polozku a vratim ziskana data uzivatele
            return $userData[0]['users_role'];
        }
        // uzivatel neni prihlasen - vracim null
        return null;
    }

    ///////////////////  KONEC: Sprava prihlaseni uzivatele  ////////////////////////////////////////

}
?>
