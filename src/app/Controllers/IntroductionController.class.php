<?php

namespace kivweb\Controllers;

// nactu rozhrani kontroleru
//require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");
use kivweb\Models\FormsCheck;
use kivweb\Models\MyDatabase;

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 * @package kivweb\Controllers
 */
class IntroductionController implements IController {

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

        // nazev
        $tplData['title'] = $pageTitle;

        //var_dump($this->db->getAllContributions());
        $tplData['contributions'] = $this->db->getContributionsByState(1);

        /*$tplData['contributions'] =array(
            array(
                "name" => "Jmeno Prispevku",
                "date" => "25.12.2022 12:35",
                "author" => "Jmeno Prijmeni",
                "abstract" => "Absstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt"
            ),
            array(
                "name" => "Jmeno Prispevku",
                "date" => "25.12.2022 12:35",
                "author" => "Jmeno Prijmeni",
                "abstract" => "Absstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt"
            ),
            array(
                "name" => "Jmeno Prispevku",
                "date" => "25.12.2022 12:35",
                "author" => "Jmeno Prijmeni",
                "abstract" => "Absstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt"
            ),
        );*/

        // vratim sablonu naplnenou daty
        return $tplData;
    }

}

?>
