<?php

namespace kivweb\Controllers;

use kivweb\Models\DatabaseModel;

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 * @package kivweb\Controllers
 */
class MyReviewsController implements IController {

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
            "assigned" => array(
                "title_group" => "Přidělené příspěvky pro recenzi",
                "color" => "info",
                "btn_text" => "Recenzovat",
                "contributions" => array(
                    array(
                        "name" => "Jmeno Prispevku",
                        "date" => "25.12.2022 12:35",
                        "abstract" => "Absstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt"
                    ),
                    array(
                        "name" => "Jmeno Prispevku",
                        "date" => "25.12.2022 12:35",
                        "abstract" => "Absstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt"
                    ),
                    array(
                        "name" => "Jmeno Prispevku",
                        "date" => "25.12.2022 12:35",
                        "abstract" => "Absstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt"
                    ),
                )
            ),
            "done" => array(
                "title_group" => "Příspěvky s recenzí",
                "btn_text" => "Upravit",
                "contributions" => array(
                    array(
                        "name" => "Jmeno Prispevku",
                        "date" => "25.12.2022 12:35",
                        "abstract" => "Absstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt"
                    ),
                    array(
                        "name" => "Jmeno Prispevku",
                        "date" => "25.12.2022 12:35",
                        "abstract" => "Absstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt"
                    ),
                    array(
                        "name" => "Jmeno Prispevku",
                        "date" => "25.12.2022 12:35",
                        "abstract" => "Absstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt"
                    ),
                )
            ),
            "published" => array(
                "title_group" => "Publikováné příspěvky",
                "color" => "success",
                "btn_text" => "Detail",
                "contributions" => array(
                    array(
                        "name" => "Jmeno Prispevku",
                        "date" => "25.12.2022 12:35",
                        "abstract" => "Absstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt"
                    ),
                    array(
                        "name" => "Jmeno Prispevku",
                        "date" => "25.12.2022 12:35",
                        "abstract" => "Absstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt"
                    ),
                    array(
                        "name" => "Jmeno Prispevku",
                        "date" => "25.12.2022 12:35",
                        "abstract" => "Absstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt"
                    ),
                )
            ),
            "dismissed" => array(
                "title_group" => "Odmítnutnuté příspěvky",
                "color" => "danger",
                "btn_text" => "Detail",
                "contributions" => array(
                    array(
                        "name" => "Jmeno Prispevku",
                        "date" => "25.12.2022 12:35",
                        "abstract" => "Absstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt"
                    ),
                    array(
                        "name" => "Jmeno Prispevku",
                        "date" => "25.12.2022 12:35",
                        "abstract" => "Absstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt"
                    ),
                    array(
                        "name" => "Jmeno Prispevku",
                        "date" => "25.12.2022 12:35",
                        "abstract" => "Absstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt"
                    ),
                )
            ),
        );

        // vratim sablonu naplnenou daty
        return $tplData;
    }

}