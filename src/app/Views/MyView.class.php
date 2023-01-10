<?php

namespace kivweb\Views;




use kivweb\Models\MyDatabase;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Trida vypisujici HTML hlavicku a paticku stranky.
 * @package kivweb\Views\Templates
 */
class MyView {

    /**
     * Zajisti vypsani HTML sablony prislusne stranky.
     * @param array $templateData       Data stranky.
     * @param string $pageKey          Typ vypisovane stranky.
     */
    public function printOutput(array $templateData, string $pageKey)
    {

        //// vypis sablony obsahu
        // data pro sablonu nastavim globalni
        global $tplData;
        $tplData = $templateData;
        $tplName = WEB_PAGES[$pageKey]['template_name'];
        // nactu sablonu
        $this->renderTwigTemplate($tplData, $tplName);
        //require_once($pageType);
    }

    /**
     * Vykresleni obsahu Twigem v.2 v sablone pro v.2.
     * Vyuziva adresar Twigu, ktery vytvoril Composer.
     * Tato verze je vice nazorna, co se tyka ukazky prace s Twigem.
     * @param array $data           Data pro sablonu.
     * @param string $templateName   Jmeno souboru se sablonou.
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function renderTwigTemplate(array $data, string $templateName){
        //$templateKey = "new_contribution";//TODO predelat na funkcni

        // nacist twig z vendor component ziskanych s vyuzitim Composer
        //require_once 'composer\vendor\autoload.php';//TODO predelat na relativni cestu k composer autolad.php
        // adresar s sablonami
        $templatesDirectory = 'app\Views\Templates';


        // cesta k adresari se sablonama - od index.php
        $loader = new FilesystemLoader($templatesDirectory);
        // vytvoreni prostredi - lze bez cache, ale pridam debug kvuli funkci dump()
        $twig = new Environment($loader, [
            'debug' => true,
            /*'cache' => 'vlastni_cache',*/
        ]);
        // pridani funkci pro debug
        $twig->addExtension(new DebugExtension());

        ///// vypsani vysledku prostrednictvim sablony
        echo $twig->render($templateName, $data);
    }

}

?>
