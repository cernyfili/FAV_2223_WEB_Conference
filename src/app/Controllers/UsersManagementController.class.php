<?php

namespace kivweb\Controllers;
use kivweb\Models\FormsCheck;
use kivweb\Models\MyDatabase;

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 * @package kivweb\Controllers
 */
class UsersManagementController implements IController {

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

        $tplData['link_page'] = array(
            "admin" => "user_detail",
            "reviewer" => "user_detail",
            "author" => "user_detail",
            "blocked" => "blocked_user_detail",
        );

        $current_statesinfo = STATES_INFO['users']['role'];

        $admin_users = $this->db->getUsersbyRole(2);
        $reviewer_users = $this->db->getUsersbyRole(1);
        $author_users = $this->db->getUsersbyRole(0);
        $blocked_users = $this->db->getUsersBlocked();

        $tplData['contribution_groups'] = array(
            $current_statesinfo[2]['name'] => array(
                "title_group" => $current_statesinfo[2]['title'],
                "color" => $current_statesinfo[2]['color'],
                "contributions" => $admin_users
            ),
            $current_statesinfo[1]['name'] => array(
                "title_group" => $current_statesinfo[1]['title'],
                "color" => $current_statesinfo[1]['color'],
                "contributions" => $reviewer_users
            ),
            $current_statesinfo[0]['name'] => array(
                "title_group" => $current_statesinfo[0]['title'],
                "color" => $current_statesinfo[0]['color'],
                "contributions" => $author_users
            ),
            "blocked" => array(
                "title_group" => "Zablokovaní uživatelé",
                "color" => "warning",
                "contributions" => $blocked_users
            ),
        );

        // vratim sablonu naplnenou daty
        return $tplData;
    }

}