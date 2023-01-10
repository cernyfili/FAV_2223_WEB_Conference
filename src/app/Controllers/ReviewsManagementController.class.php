<?php

namespace kivweb\Controllers;
use kivweb\Models\FormsCheck;
use kivweb\Models\MyDatabase;

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 * @package kivweb\Controllers
 */
class ReviewsManagementController implements IController {

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

        if($loggedRole != 2){
            header('Location: index.php?page=error');
            exit;
        }

        //// vsechna data sablony budou globalni

        // nazev
        $tplData['title'] = $pageTitle;

        $sections = array(
            "done",
            "not_assigned",
            "assigned",
            "published",
            "dismissed",
        );

        $tplData['link_page'] = array(
            $sections[0] => "contribution_detail_management",
            $sections[1] => "review_assignment",
            $sections[2] => "edit_review_assignment",
            $sections[3] => "contribution_detail_management",
            $sections[4] => "contribution_detail_management",
        );

        $donereviews_managereviewscontributions = array();
        $assigned_managereviewscontributions = array();
        $notassigned_managereviewscontributions = array();


        $reviewsAssi_done = $this->db->getReviewAssignByState(0);
        $reviewsAssi_workingon = $this->db->getReviewAssignByState(1);

        $contributions_workingon = $this->db->getContributionsByState(0);


        foreach ($contributions_workingon as $contribution){
            $set = false;

            foreach ($reviewsAssi_done as $reviewAssi){
                if(in_array($contribution['contributions_id_contributions'], $reviewAssi)){
                    $donereviews_managereviewscontributions[] = $contribution;
                    $set = true;
                    break;
                }
            }
            if ($set) continue;

            foreach ($reviewsAssi_workingon as $reviewAssi){
                if(in_array($contribution['contributions_id_contributions'], $reviewAssi)){
                    $assigned_managereviewscontributions[] = $contribution;
                    $set = true;
                    break;
                }
            }
            if ($set) continue;

            $notassigned_managereviewscontributions[] = $contribution;
        }








        $published_managereviewscontributions = $this->db->getContributionsByState(1);
        $dismissed_managereviewscontributions = $this->db->getContributionsByState(2);

        $tplData['contribution_groups'] = array(
            $sections[0] => array(
                "title_group" => "Příspěvky s recenzemi",
                "color" => "primary",
                "btn_text" => "Publikovat",
                "contributions" => $donereviews_managereviewscontributions
            ),
            $sections[1] => array(
                "title_group" => "Nepřidělené příspěvky",
                "btn_text" => "Přidělit",
                "contributions" => $notassigned_managereviewscontributions
            ),
            $sections[2] => array(
                "title_group" => "Přidělené příspěvky",
                "btn_text" => "Upravit",
                "contributions" => $assigned_managereviewscontributions
            ),
            $sections[3] => array(
                "title_group" => "Publikováno",
                "color" => "success",
                "btn_text" => "Upravit",
                "contributions" => $published_managereviewscontributions
            ),
            $sections[4] => array(
                "title_group" => "Odmítnuto",
                "color" => "danger",
                "btn_text" => "Upravit",
                "contributions" => $dismissed_managereviewscontributions
            ),
        );

        // vratim sablonu naplnenou daty
        return $tplData;
    }

}