<?php

namespace kivweb\Controllers;
use kivweb\Models\FormsCheck;
use kivweb\Models\MyDatabase;

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 * @package kivweb\Controllers
 */
class EditLoginInfoController implements IController {

    /** @var MyDatabase $db  Sprava databaze. */
    private $db;

    private $formsCheck;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        // inicializace prace s DB
        //require_once (DIRECTORY_MODELS ."/MyDatabase.class.php");
        $this->db = MyDatabase::getMyDatabase();
        $this->formsCheck = FormsCheck::getMyFormsCheck();
    }

    /**
     * Vrati obsah uvodni stranky.
     * @param string $pageTitle     Nazev stranky.
     * @return array                Vytvorena data pro sablonu.
     */
    public function show(string $pageTitle):array {

        $this->formsCheck->checkLoginLogout();
        $this->formsCheck->checkEditLogin();


        $tplData = [];

        /*-- GLOBAL --*/
        $loggedUserData = $this->db->getLoggedUserData();
        $loggedRole = $this->db->getLoggedUserRole();
        if ($loggedUserData!=null) {
            $tplData['logged_user'] = $loggedUserData[0];
            $tplData['logged_role'] = $loggedRole;
        }

        if(isset($_GET['page'])){
            $tplData['page'] = htmlspecialchars($_GET['page']);
        }
        /*-- END: GLOBAL --*/

        //// vsechna data sablony budou globalni

        // nazev
        $tplData['title'] = $pageTitle;

        $userInfoarr = $loggedUserData;
        if ($userInfoarr!=null) {
            $tplData['user'] = $userInfoarr[0];
        }
        else{
            header('Location: index.php?page=error');
            exit;
        }

        // vratim sablonu naplnenou daty
        return $tplData;
    }

}