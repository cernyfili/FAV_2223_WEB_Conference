<?php
///////////////////////////////////////////////////////////////////
////////////// Stranka pro spravu uzivatelu ////////////////
///////////////////////////////////////////////////////////////////

    // nacteni souboru s funkcemi
    require_once("MyDatabase.class.php");
    $myDB = new MyDatabase();

    // nacteni hlavicky stranky
    require_once("ZakladHTML.class.php");
    ZakladHTML::createHeader("Správa uživatelů");

    // pokud je uzivatel prihlasen, tak ziskam jeho data
    if($myDB->isUserLogged()){
        // ziskam data prihlasenoho uzivatele
        $userData = $myDB->getLoggedUserData();
    }

    ///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
    if(!$myDB->isUserLogged()){
?>
        <div>
            <b>Tato strána je dostupná pouze přihlášeným uživatelům.</b>
        </div>
<?php
    ///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////
    } else if($userData['id_pravo'] > 2) {
    ///////////// PRO PRIHLASENE UZIVATELE BEZ PRAVA ADMIN ///////////////
?>
        <div>
            <b>Správu uživatelů mohou provádět pouze uživatelé s právem Administrátor.</b>
        </div>
<?php
    ///////////// KONEC: PRO PRIHLASENE UZIVATELE BEZ PRAVA ADMIN ///////////////
    } else {
    ///////////// PRO PRIHLASENE UZIVATELE S PRAVEM ADMIN ///////////////

        // zpracovani formulare pro smazani uzivatele
        if(!empty($_POST['id_uzivatel'])){
            // smazu daneho uzivatele z databaze
            $res = $myDB->deleteFromTable(TABLE_UZIVATEL, "id_uzivatel='$_POST[id_uzivatel]'");
            // vysledek mazani
            if($res){
                echo "OK: Uživatel byl smazán z databáze.";
            } else {
                echo "ERROR: Smazání uživatele se nezdařilo.";
            }
        }

        // ziskam data vsech uzivatelu
        $users = $myDB->getAllUsers();
?>
        <h2>Seznam uživatelů</h2>
        <table border="1">
            <tr><th>ID</th><th>Login</th><th>Jméno</th><th>E-mail</th><th>Právo</th><th>Akce</th></tr>
            <?php
                // projdu uzivatele a vypisu je
                foreach ($users as $u) {
                    echo "<tr><td>$u[id_uzivatel]</td><td>$u[login]</td><td>$u[jmeno]</td><td>$u[email]</td><td>$u[id_pravo]</td><td>"
                        ."<form action='' method='POST'>
                              <input type='hidden' name='id_uzivatel' value='$u[id_uzivatel]'>
                              <input type='submit' name='potvrzeni' value='Smazat'>
                          </form>"
                        ."</td></tr>";
                }
            ?>
        </table>
<?php
    /* // akce by mela obsahovat formular s tlacitkem:
        <form action='' method='POST'>
            <input type='hidden' name='id_uzivatel' value='....'>
            <input type='submit' name='potvrzeni' value='Smazat'>
        </form>
    */
    ///////////// KONEC: PRO PRIHLASENE UZIVATELE S PRAVEM ADMIN ///////////////
    }

    // paticka
    ZakladHTML::createFooter();
?>
