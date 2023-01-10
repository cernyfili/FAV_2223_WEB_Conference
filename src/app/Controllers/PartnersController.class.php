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
class PartnersController implements IController {

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

        //// vsechna data sablony budou globalni

        // nazev
        $tplData['title'] = $pageTitle;

        $tplData['partners'] = array(
            array(
                "website" => "https://www.fav.zcu.cz/cs/",
                "image" => "FAV_logo.png",
                "name" => "Fakulta aplikovaných věd",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. In nisi nisl, malesuada quis mi et, vestibulum auctor risus. Pellentesque vitae lectus neque. Morbi id leo in nulla aliquet pellentesque. Quisque in suscipit eros, id suscipit libero. Curabitur suscipit felis in diam vestibulum, non finibus urna scelerisque. Nam porta, mauris in sodales laoreet, lacus ante dictum dolor, eget congue arcu tellus non nulla. Vivamus facilisis odio ut porttitor laoreet. Ut condimentum, odio eget consectetur ultricies, arcu lectus efficitur velit, condimentum finibus turpis dui eget ex. Duis ut tempor arcu.",
            ),
            array(
                "website" => "https://www.fav.zcu.cz/cs/",
                "image" => "FAV_logo.png",
                "name" => "Fakulta aplikovaných věd",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. In nisi nisl, malesuada quis mi et, vestibulum auctor risus. Pellentesque vitae lectus neque. Morbi id leo in nulla aliquet pellentesque. Quisque in suscipit eros, id suscipit libero. Curabitur suscipit felis in diam vestibulum, non finibus urna scelerisque. Nam porta, mauris in sodales laoreet, lacus ante dictum dolor, eget congue arcu tellus non nulla. Vivamus facilisis odio ut porttitor laoreet. Ut condimentum, odio eget consectetur ultricies, arcu lectus efficitur velit, condimentum finibus turpis dui eget ex. Duis ut tempor arcu.",
            ),
            array(
                "website" => "https://www.fav.zcu.cz/cs/",
                "image" => "FAV_logo.png",
                "name" => "Fakulta aplikovaných věd",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. In nisi nisl, malesuada quis mi et, vestibulum auctor risus. Pellentesque vitae lectus neque. Morbi id leo in nulla aliquet pellentesque. Quisque in suscipit eros, id suscipit libero. Curabitur suscipit felis in diam vestibulum, non finibus urna scelerisque. Nam porta, mauris in sodales laoreet, lacus ante dictum dolor, eget congue arcu tellus non nulla. Vivamus facilisis odio ut porttitor laoreet. Ut condimentum, odio eget consectetur ultricies, arcu lectus efficitur velit, condimentum finibus turpis dui eget ex. Duis ut tempor arcu.",
            )
        );

        // vratim sablonu naplnenou daty
        return $tplData;
    }

}

?>
