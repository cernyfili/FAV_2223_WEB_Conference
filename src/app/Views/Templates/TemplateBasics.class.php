<?php

namespace kivweb\Views\Templates;

use kivweb\Views\IView;

/**
 * Trida vypisujici HTML hlavicku a paticku stranky.
 * @package kivweb\Views\Templates
 */
class TemplateBasics implements IView {

    /** @var string PAGE_INTRODUCTION  Sablona s uvodni strankou. */
    const PAGE_INTRODUCTION = "site-intro.twig";
    /** @var string PAGE_USER_MANAGEMENT  Sablona se spravou uzivatelu. */
    const PAGE_USER_MANAGEMENT = "UserManagementTemplate.tpl.php";

    /**
     * Zajisti vypsani HTML sablony prislusne stranky.
     * @param array $templateData       Data stranky.
     * @param string $pageType          Typ vypisovane stranky.
     */
    public function printOutput(array $templateData, string $pageType = self::PAGE_INTRODUCTION)
    {

        //// vypis sablony obsahu
        // data pro sablonu nastavim globalni
        global $tplData;
        $tplData = $templateData;
        // nactu sablonu
        $this->renderTwigTemplate($tplData, $pageType);
        //require_once($pageType);
    }

    /**
     * Vykresleni obsahu Twigem v.2 v sablone pro v.2.
     * Vyuziva adresar Twigu, ktery vytvoril Composer.
     * Tato verze je vice nazorna, co se tyka ukazky prace s Twigem.
     * @param array $data           Data pro sablonu.
     * @param string $templateKey   Klic pro prislusnou stranku.
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function renderTwigTemplate(array $data, string $templateKey){
        $templateKey = "uvod";//TODO predelat na funkcni

        // definice sablon prislusnych stranek
        // zde doporucuji pouzit variantu z predesleho cviceni, kde stranky webu jsou ulozeny v konstante
        $webTemplates = array(
            "uvod" => "site-intro.twig",
            "obchod" => "site-market.twig"
        );

        // nacist twig z vendor component ziskanych s vyuzitim Composer
        require_once 'C:\Users\Lenovo\Documents\GitHub\WEB_semestralka\src\composer\vendor\autoload.php';//TODO predelat na relativni cestu k composer autolad.php
        // adresar s sablonami
        $templatesDirectory = 'app\Views\Templates';
        // nyni system vyuziva jen jednu sablonu pro vypis obou stranek
        // pri pouziti vice sablon musi byt spravna sablona zvolena zde
        $currentTemplateName = $webTemplates[$templateKey];

        // cesta k adresari se sablonama - od index.php
        $loader = new \Twig\Loader\FilesystemLoader($templatesDirectory);
        // vytvoreni prostredi - lze bez cache, ale pridam debug kvuli funkci dump()
        $twig = new \Twig\Environment($loader, [
            'debug' => true,
            /*'cache' => 'vlastni_cache',*/
        ]);
        // pridani funkci pro debug
        $twig->addExtension(new \Twig\Extension\DebugExtension());

        ///// vypsani vysledku prostrednictvim sablony
        echo $twig->render($currentTemplateName, $data);
    }

}

?>
