<?php

namespace kivweb\Controllers;
use kivweb\Models\FormsCheck;
use kivweb\Models\MyDatabase;

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 * @package kivweb\Controllers
 */
class MyContributionsController implements IController {

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


        $tplData['link_page'] = array(
            "working_on" => "edit_contribution",
            "published" => "contribution_detail_management",
            "dismissed" => "contribution_detail_management",
        );

        $current_statesinfo = STATES_INFO['contributions']['state'];
        $id_loggeduser = $this->db->getLoggedUserId();

        if ($id_loggeduser != null AND $loggedRole == 0) {

            $workingon_id = 0;
            $workingon_contributions = $this->db->getContributionsByStateIduser($workingon_id, $id_loggeduser);
            $published_id = 1;
            $published_contributions = $this->db->getContributionsByStateIduser($published_id, $id_loggeduser);
            $dismissed_id = 2;
            $dismissed_contributions = $this->db->getContributionsByStateIduser($dismissed_id, $id_loggeduser);


            $tplData['contribution_groups'] = array(
                $current_statesinfo[$workingon_id]['name'] => array(
                    "title_group" => $current_statesinfo[$workingon_id]['title'],
                    "color" => $current_statesinfo[$workingon_id]['color'],
                    "btn_text" => "Upravit",
                    "contributions" => $workingon_contributions
                ),
                $current_statesinfo[$published_id]['name'] => array(
                    "title_group" => $current_statesinfo[$published_id]['title'],
                    "color" => $current_statesinfo[$published_id]['color'],
                    "btn_text" => "Detail",
                    "contributions" => $published_contributions
                ),
                $current_statesinfo[$dismissed_id]['name'] => array(
                    "title_group" => $current_statesinfo[$dismissed_id]['title'],
                    "color" => $current_statesinfo[$dismissed_id]['color'],
                    "btn_text" => "Detail",
                    "contributions" => $dismissed_contributions
                ),
            );
        }

        //var_dump($tplData);

        // vratim sablonu naplnenou daty
        return $tplData;
    }

}