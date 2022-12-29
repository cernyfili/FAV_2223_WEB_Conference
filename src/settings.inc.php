<?php
//////////////////////////////////////////////////////////////////
/////////////////  Globalni nastaveni aplikace ///////////////////
//////////////////////////////////////////////////////////////////

use kivweb\Controllers\IntroductionController;
use kivweb\Controllers\UserManagementController;
use kivweb\Views\Templates\TemplateBasics;

//// Pripojeni k databazi ////

/** Adresa serveru. */

define("DB_SERVER","localhost"); // https://students.kiv.zcu.cz lze 147.228.63.10, ale musite byt na VPN
/** Nazev databaze. */
define("DB_NAME","cernyf_mvc_introdution");
/** Uzivatel databaze. */
define("DB_USER","root");
/** Heslo uzivatele databaze */
define("DB_PASS","");


//// Nazvy tabulek v DB ////

/** Tabulka s pohadkami. */
define("TABLE_INTRODUCTION", "cernyf_mvc_introduction");
/** Tabulka s uzivateli. */
define("TABLE_USER", "cernyf_mvc_user");


//// Dostupne stranky webu ////

/** Adresar kontroleru. */
//const DIRECTORY_CONTROLLERS = "Controllers";
/** Adresar modelu. */
//const DIRECTORY_MODELS = "Models";
/** Adresar sablon */
//const DIRECTORY_VIEWS = "Views";

/** Klic defaultni webove stranky. */
const DEFAULT_WEB_PAGE_KEY = "intro";

/** Dostupne webove stranky. */
    const WEB_PAGES = array(
    //// Uvodni stranka ////
    "intro" => array(
        "title" => "Úvodní stránka",

        //// kontroler
        "controller_class_name" => IntroductionController::class, // poskytne nazev tridy vcetne namespace

        // Templates sablona
        "view_class_name" => TemplateBasics::class,
        "template_file" => "site-intro.twig"
    ),
    //// KONEC: Uvodni stranka ////

    //// Novy pripsevek ////
    "new_contribution" => array(
        "title" => "Nový příspěvek",

        //// kontroler
        "controller_class_name" => IntroductionController::class, // poskytne nazev tridy vcetne namespace

        // Templates sablona
        "view_class_name" => TemplateBasics::class,
        "template_file" => "site-new_contribution.twig"
    ),
    //// KONEC: Novy prispevek ////

);

?>
