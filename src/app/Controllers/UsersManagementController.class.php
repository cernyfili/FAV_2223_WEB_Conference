<?php

namespace kivweb\Controllers;

use kivweb\Models\DatabaseModel;

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 * @package kivweb\Controllers
 */
class UsersManagementController implements IController {

    /** @var DatabaseModel $db  Sprava databaze. */
    private $db;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        // inicializace prace s DB
        //require_once (DIRECTORY_MODELS ."/DatabaseModel.class.php");
        $this->db = DatabaseModel::getDatabaseModel();
    }

    /**
     * Vrati obsah uvodni stranky.
     * @param string $pageTitle     Nazev stranky.
     * @return array                Vytvorena data pro sablonu.
     */
    public function show(string $pageTitle):array {
        //// vsechna data sablony budou globalni
        $tplData = [];
        // nazev
        $tplData['title'] = $pageTitle;

        $tplData['contribution_groups'] = array(
            "admin" => array(
                "title_group" => "Administrátoři",
                "color" => "info",
                "contributions" => array(
                    array(
                        "name" => "Jmeno Prijmeni",
                        "login" => "login",
                        "email" => "email@email.cz"
                    ),
                    array(
                        "name" => "Jmeno Prijmeni",
                        "login" => "login",
                        "email" => "email@email.cz"
                    ),
                    array(
                        "name" => "Jmeno Prijmeni",
                        "login" => "login",
                        "email" => "email@email.cz"
                    ),
                )
            ),
            "reviewer" => array(
                "title_group" => "Recenzenti",
                "color" => "success",
                "contributions" => array(
                    array(
                        "name" => "Jmeno Prijmeni",
                        "login" => "login",
                        "email" => "email@email.cz"
                    ),
                    array(
                        "name" => "Jmeno Prijmeni",
                        "login" => "login",
                        "email" => "email@email.cz"
                    ),
                    array(
                        "name" => "Jmeno Prijmeni",
                        "login" => "login",
                        "email" => "email@email.cz"
                    ),
                )
            ),
            "author" => array(
                "title_group" => "Autoři",
                "contributions" => array(
                    array(
                        "name" => "Jmeno Prijmeni",
                        "login" => "login",
                        "email" => "email@email.cz"
                    ),
                    array(
                        "name" => "Jmeno Prijmeni",
                        "login" => "login",
                        "email" => "email@email.cz"
                    ),
                    array(
                        "name" => "Jmeno Prijmeni",
                        "login" => "login",
                        "email" => "email@email.cz"
                    ),
                )
            ),
        );

        // vratim sablonu naplnenou daty
        return $tplData;
    }

}