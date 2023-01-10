<?php

namespace kivweb\Controllers;
use kivweb\Models\FormsCheck;
use kivweb\Models\MyDatabase;

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 * @package kivweb\Controllers
 */
class ContributionDetailManagementController implements IController {

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
        $this->formsCheck->checkContributionManagement($_GET['id']);

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



        if(!isset($_GET['id']) || $_GET['id']==""){
            header('Location: index.php?page=error');
            exit;
        }
        // var_dump($this->db->getContributionInfo($_GET['id']));

        $id = htmlspecialchars($_GET['id']);
        $contributionInfoarr = $this->db->getContributionInfobyId($id);
        if ($contributionInfoarr!=null) {
            $tplData['contribution'] = $contributionInfoarr[0];
        }
        else{
            header('Location: index.php?page=error');
            exit;
        }

        $current_statesinfo = STATES_INFO['contributions']['state'][$tplData['contribution']['contributions_state']];

        $tplData['contribution_group'] = array(
            'name' => $current_statesinfo['name'],
            'title' => $current_statesinfo['title'],
            'color' => $current_statesinfo['color']
        );

        //CONTRIBUTION FILES
        $contributionFiles = $this->db->getContributionFilesbyIdcontribution($id);
        $tplData['file'] = array();
        foreach ($contributionFiles as $file){
            $tplData['file'][] = array(
                "file_content" => base64_encode($file['contributions_files_file']),
                "data" => $file
            );
        }

        $tplData['reviews'] = $this->db->getReviewsbyIdContribution($id);


        // vratim sablonu naplnenou daty
        return $tplData;
    }

}