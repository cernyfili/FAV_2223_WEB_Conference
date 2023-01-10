<?php

namespace kivweb\Controllers;

use kivweb\Models\FormsCheck;
use kivweb\Models\MyDatabase;

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 * @package kivweb\Controllers
 */
class MyReviewsController implements IController {

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

        $sections = array(
            "assigned",
            "done",
            "published",
            "dismissed",
        );

        $tplData['link_page'] = array(
            $sections[0] => "new_review",
            $sections[1] => "edit_review",
            $sections[2] => "contribution_detail_management",
            $sections[3] => "contribution_detail_management",
        );


        $id_loggeduser = $this->db->getLoggedUserId();

        if ($id_loggeduser != null AND $loggedRole == 1) {

            $assigned_reviewcontributions = $this->db->getReviewContributionsbyIdReviewer($id_loggeduser, 0, 1);
            $done_reviewcontributions = $this->db->getReviewContributionsbyIdreviewer($id_loggeduser, 0, 0);
            $published_reviewcontributions = $this->db->getReviewContributionsbyIdreviewer($id_loggeduser, 1, 0);
            $dismissed_reviewcontributions = $this->db->getReviewContributionsbyIdreviewer($id_loggeduser, 2, 0);


            $tplData['contribution_groups'] = array(
                $sections[0] => array(
                    "title_group" => "Přidělené příspěvky pro recenzi",
                    "color" => "primary",
                    "btn_text" => "Recenzovat",
                    "contributions" => $assigned_reviewcontributions
                ),
                $sections[1] => array(
                    "title_group" => "Příspěvky s recenzí",
                    "btn_text" => "Upravit",
                    "contributions" => $done_reviewcontributions
                ),
                $sections[2] => array(
                    "title_group" => "Publikováné příspěvky",
                    "color" => "success",
                    "btn_text" => "Detail",
                    "contributions" => $published_reviewcontributions
                ),
                $sections[3] => array(
                    "title_group" => "Odmítnutnuté příspěvky",
                    "color" => "danger",
                    "btn_text" => "Detail",
                    "contributions" => $dismissed_reviewcontributions
                )
            );
        }

        // vratim sablonu naplnenou daty
        return $tplData;
    }

}