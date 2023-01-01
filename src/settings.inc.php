<?php
//////////////////////////////////////////////////////////////////
/////////////////  Globalni nastaveni aplikace ///////////////////
//////////////////////////////////////////////////////////////////

use kivweb\Controllers\ContributionDetailController;
use kivweb\Controllers\EditContributionController;
use kivweb\Controllers\EditLoginInfoController;
use kivweb\Controllers\EditReviewController;
use kivweb\Controllers\IntroductionController;
use kivweb\Controllers\MyContributionsController;
use kivweb\Controllers\MyReviewsController;
use kivweb\Controllers\NewContributionController;
use kivweb\Controllers\RegistrationController;
use kivweb\Controllers\ReviewAssignmentController;
use kivweb\Controllers\ReviewsManagementController;
use kivweb\Controllers\UsersManagementController;

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
        // Uvodni starnka //
        "intro" => array(
            "title" => "Úvodní stránka",
            "controller_class_name" => IntroductionController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "site-intro.twig"
        ),
        // END: Uvodni stranka //

        // Registrace - novy uzivatel //
        "registration" => array(
            "title" => "Registrace",
            "controller_class_name" => RegistrationController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "site-registration.twig"
        ),
        // KONEC: Registrace //

        // Detail přispěvku //
        "contribution_detail" => array(
            "title" => "Detail příspěvku",
            "controller_class_name" => ContributionDetailController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "site-contribution_detail.twig"
        ),
        // KONEC: Detail přispěvku //

        // Zmena uzivateslkych udaju //
        "edit_login" => array(
            "title" => "Změna uživatelských údajů",
            "controller_class_name" => EditLoginInfoController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "site-edit_login_info.twig"
        ),
        // KONEC: Zmena uzivateslkych udaju //



        //// Management stranky - pro ruzne uzivatelske role ////


        /// Autor ///

        // Moje prispevky //
        "my_contributions" => array(
            "title" => "Moje přípěvky",
            "controller_class_name" => MyContributionsController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "template-management.twig"
        ),
        // END: Moje prispevky //

        // Uprava prispevku //
        "edit_contribution" => array(
            "title" => "Úprava příspěvku",
            "controller_class_name" => EditContributionController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "site-form_contribution.twig"
        ),
        // END: Uprava prispevky //

        // Novy pripsevek //
        "new_contribution" => array(
            "title" => "Nový příspěvek",
            "controller_class_name" => NewContributionController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "site-form_contribution.twig"
        ),
        // KONEC: Novy prispevek //

        /// END: Autor ///


        /// Recenzent ///

        // Moje recenze //
        "my_reviews" => array(
            "title" => "Moje recenze",
            "controller_class_name" => MyReviewsController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "template-management.twig"
        ),
        // KONEC: Moje recenze //

        // Uprava recenze //
        "edit_review" => array(
            "title" => "Recenze",
            "controller_class_name" => EditReviewController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "site-form_review.twig"
        ),
        // KONEC: Uprava recenze //

        /// END: Recenzent ///


        /// Administrator ///

        // Sprava recenzi //
        "reviews_management" => array(
            "title" => "Správa recenzí",
            "controller_class_name" => ReviewsManagementController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "template-management.twig"
        ),
        // KONEC: Sprava recenzi //

        // Prideleni recenze //
        "review_assignment" => array(
            "title" => "Přidělení recenze",
            "controller_class_name" => ReviewAssignmentController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "site-form_review_assignment.twig"
        ),
        // KONEC: Prideleni recenze //

        // Sprava uzivatelu //
        "users_management" => array(
            "title" => "Správa uživatelů",
            "controller_class_name" => UsersManagementController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "template-management.twig"
        ),
        // KONEC: Sprava uzivatelu //

        /// END: Administrator ///


        //// END: Management stranky ////





);

?>
