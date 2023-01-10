<?php

namespace kivweb\Controllers;
use kivweb\Models\FormsCheck;
use kivweb\Models\MyDatabase;

// nactu rozhrani kontroleru
//require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 * @package kivweb\Controllers
 */
class BasicSiteController implements IController {

    /** @var MyDatabase $db  Sprava databaze. */
    private $db;

    private $formsCheck;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        // inicializace prace s DB
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

        $tplData['title'] = $pageTitle;

        if(isset($_GET['error_text'])){
            $tplData['text'] = htmlspecialchars($_GET['error_text']);
        }

        // vratim sablonu naplnenou daty
        return $tplData;
    }

}

?>
