<?php
//////////////////////////////////////////////////////////////////
/////////////////  Globalni nastaveni aplikace ///////////////////
//////////////////////////////////////////////////////////////////

use kivweb\Controllers\BasicSiteController;
use kivweb\Controllers\ContactsController;
use kivweb\Controllers\ContributionDetailController;
use kivweb\Controllers\ContributionDetailManagementController;
use kivweb\Controllers\EditContributionController;
use kivweb\Controllers\EditLoginInfoController;
use kivweb\Controllers\EditReviewController;
use kivweb\Controllers\FileDownloadController;
use kivweb\Controllers\IntroductionController;
use kivweb\Controllers\MyContributionsController;
use kivweb\Controllers\MyReviewsController;
use kivweb\Controllers\NewContributionController;
use kivweb\Controllers\PartnersController;
use kivweb\Controllers\RegistrationController;
use kivweb\Controllers\ReviewAssignmentController;
use kivweb\Controllers\ReviewsManagementController;
use kivweb\Controllers\UserDetailController;
use kivweb\Controllers\UsersManagementController;

//// Pripojeni k databazi ////

/** Adresa serveru. */

define("DB_SERVER","localhost"); // https://students.kiv.zcu.cz lze 147.228.63.10, ale musite byt na VPN
/** Nazev databaze. */
define("DB_NAME","conference");
/** Uzivatel databaze. */
define("DB_USER","root");
/** Heslo uzivatele databaze */
define("DB_PASS","");


//// Nazvy tabulek v DB ////


const DB_TABLES = array(
    "contributions" => "contributions",
    "users" => "users",
    "contributions_files" => "contributions_files",
    "reviews" => "reviews",
    "reviews_assignments" => "reviews_assignments"
);

//// Dostupne stranky webu ////

/** Adresar kontroleru. */
//const DIRECTORY_CONTROLLERS = "Controllers";
/** Adresar modelu. */
//const DIRECTORY_MODELS = "Models";
/** Adresar sablon */
//const DIRECTORY_VIEWS = "Views";

/** Nazvy ciselnych stavu z databaze **/
const STATES_INFO = array(
    "reviews" => array(
        "topic_review" => array(
            0 => "Vůbec",
            1 => "Možná",
            2 => "Určitě",
        ),
        "author_review" => array(
            0 => "Nechopen",
            1 => "Schopen",
            2 => "Kvalifikovaný",
        ),
        "abstract_review" => array(
            0 => "Nevýstižný",
            1 => "Výstižný",
            2 => "Výborný",
        ),
        "type" => array(
            0 => "",
            1 => "",
        ),
    ),
    "users" => array(
        "role" => array(
            0 => array(
                "name" => "author",
                "title" => "Autor",
                "color" => "dark",
            ),
            1 => array(
                "name" => "reviewer",
                "title" => "Recenzent",
                "color" => "success",
            ),
            2 => array(
                "name" => "admin",
                "title" => "Administrátor",
                "color" => "primary",
            ),
        ),
    ),
    "reviews_assignments" => array(
        "state" => array(
            0 => "",
            1 => "",
        ),
    ),
    "contributions" => array(
        "state" => array(
            0 => array(
                "name" => "working_on",
                "title" => "V recenzním řízení",
                "color" => "dark",
            ),
            1 => array(
                "name" => "published",
                "title" => "Publikováno",
                "color" => "success",

            ),
            2 => array(
                "name" => "dismissed",
                "title" => "Odmítnuto",
                "color" => "danger",

            ),
        ),
    ),
);

/** Klic defaultni webove stranky. */
const DEFAULT_WEB_PAGE_KEY = "intro";

/** Dostupne webove stranky. */
const WEB_PAGES = array(

        // Uvodni starnka //
        "error" => array(
            "title" => "Chyba",
            "controller_class_name" => BasicSiteController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "site-error.twig"
        ),
        // END: Uvodni stranka //

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

        // Partneri stranka //
        "partners" => array(
            "title" => "Partneři konference",
            "controller_class_name" => PartnersController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "site-partners.twig"
        ),
        // KONEC: Partneri stranka //

        // Kontakty stranka //
        "contacts" => array(
            "title" => "Místo a kontakty",
            "controller_class_name" => BasicSiteController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "site-contacts.twig"
        ),
        // KONEC: Kontakty stranka //


        //// Management stranky - pro ruzne uzivatelske role ////

        // Detail prispevek (i s recenzemi) //
        "contribution_detail_management" => array(
            "title" => "Detail příspěvku",
            "controller_class_name" => ContributionDetailManagementController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "site-contribution_detail_management.twig"
        ),
        // KONEC: Detail prispevek //


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
        "new_review" => array(
            "title" => "Recenze",
            "controller_class_name" => EditReviewController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "site-form_review.twig"
        ),
        // KONEC: Uprava recenze //

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

        // Uprava prideleni recenze //
        "edit_review_assignment" => array(
            "title" => "Úprava přidělení recenze",
            "controller_class_name" => ReviewAssignmentController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "site-form_review_assignment.twig"
        ),
        // KONEC: Uprava prideleni recenze //

        // Sprava uzivatelu //
        "users_management" => array(
            "title" => "Správa uživatelů",
            "controller_class_name" => UsersManagementController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "template-management.twig"
        ),
        // KONEC: Sprava uzivatelu //

        // Detailuzivatelu//
        "user_detail" => array(
            "title" => "Detail uživatele",
            "controller_class_name" => UserDetailController::class, // poskytne nazev tridy vcetne namespace
            "template_name" => "site-user_detail.twig"
        ),
        // KONEC: Detail uzivatel //

        // Detailuzivatelu//
        "blocked_user_detail" => array(
                "title" => "Detail uživatele",
                "controller_class_name" => UserDetailController::class, // poskytne nazev tridy vcetne namespace
                "template_name" => "site-user_detail.twig"
        ),
        // KONEC: Detail uzivatel //

        /// END: Administrator ///


        //// END: Management stranky ////





);

?>
