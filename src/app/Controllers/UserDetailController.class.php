<?php

namespace kivweb\Controllers;
use kivweb\Models\FormsCheck;
use kivweb\Models\MyDatabase;

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 * @package kivweb\Controllers
 */
class UserDetailController implements IController {

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
        $this->formsCheck->checkUserDetail($_GET['id']);


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

        $roles_info = STATES_INFO['users']['role'];

        if(!isset($_GET['id']) || $_GET['id']==""){
            header('Location: index.php?page=error');
            exit;
        }
        // var_dump($this->db->getContributionInfo($_GET['id']));

        $id = htmlspecialchars($_GET['id']);
        $usersarr = $this->db->getUserbyId_Block($id);
        if ($usersarr!=null) {
            $tplData['user'] = $usersarr[0];
        }
        else{
            header('Location: index.php?page=error');
            exit;
        }

        foreach ($roles_info as $key => $role_info){
            $tplData['form_role_info'][] = array(
                "users_id_users" => $key,
                "users_name" => $role_info['title'],
            );
        }

        $selected_role_id = $tplData['user']['users_role'];
        $selected_role_name = $roles_info[$selected_role_id]['title'];
        $selected_color = $roles_info[$selected_role_id]['color'];

        $tplData['selected_role'] = array(
            array(
                'reviews_assignments_id_users' => $selected_role_id,
                'name' => $selected_role_name,
                'color' => $selected_color
            )
        );

        // vratim sablonu naplnenou daty
        return $tplData;
    }

}