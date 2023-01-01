<?php

namespace kivweb\Controllers;

use kivweb\Models\DatabaseModel;

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 * @package kivweb\Controllers
 */
class EditContributionController implements IController {

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

        $tplData['contribution'] = array(
            'title' => "Nazev prispevku",
            'author' => "Jmeno Prijmeni",
            'date' => "25.12.2022 12:35",
            'abstract' => "Abstrakt abstrakt  abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt abstrakt",
            'files' => array(
                array(
                    'name' => "pdf_file.pdf"
                ),
                array(
                    'name' => "pdf_file2.pdf"
                )
            )
        );

        // vratim sablonu naplnenou daty
        return $tplData;
    }

}