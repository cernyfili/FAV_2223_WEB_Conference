<?php
///////////////////////////////////////////////////////////////////
////////////// Stranka pro upravu osobnich udaju uzivatele ////////////////
///////////////////////////////////////////////////////////////////

    // nacteni souboru s funkcemi
    require_once("MyDatabase.class.php");
    $myDB = new MyDatabase();

    // nacteni hlavicky stranky
    require_once("ZakladHTML.class.php");
    ZakladHTML::createHeader("Úprava osobních údajů uživatele");

    // pokud je uzivatel prihlasen, tak ziskam jeho data
    if($myDB->isUserLogged()){
        // ziskam data prihlasenoho uzivatele
        $userData = $myDB->getLoggedUserData();
    }

    ///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
    if(!$myDB->isUserLogged()){
?>
        <div>
            <b>Osobní údaje mohou měnit pouze přihlášení uživatelé.</b>
        </div>
<?php
    ///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////
    } else {
    ///////////// PRO PRIHLASENE UZIVATELE ///////////////

        // zpracovani odeslanych formularu
        if(isset($_POST['potvrzeni'])){
            // mam vsechny pozadovane hodnoty?
            if(isset($_POST['id_uzivatel']) && isset($_POST['heslo']) && isset($_POST['heslo2'])
                && isset($_POST['jmeno']) && isset($_POST['email']) && isset($_POST['pravo'])
                && $_POST['heslo'] == $_POST['heslo2']
                && $_POST['heslo'] != "" && $_POST['jmeno'] != "" && $_POST['email'] != ""
                && $_POST['pravo'] > 0
                // je soucasnym uzivatelem a zadal spravne heslo?
                && $_POST['id_uzivatel'] == $userData['id_uzivatel']
            ){
                // bylo zadano sprevne soucasne heslo?
                if($_POST['heslo_puvodni'] == $userData['heslo']){
                    // bylo a mam vsechny atributy - ulozim uzivatele do DB
                    $res = $myDB->updateUser($userData['id_uzivatel'], $userData['login'], $_POST['heslo'], $_POST['jmeno'], $_POST['email'], $_POST['pravo']);
                    // byl ulozen?
                    if($res){
                        echo "OK: Uživatel byl upraven.";
                        // nactu znovu jeho aktualni data
                        $userData = $myDB->getLoggedUserData();
                    } else {
                        echo "ERROR: Upravení uživatele se nezdařilo.";
                    }
                } else {
                    // nebylo
                    echo "ERROR: Zadané současné heslo uživatele není správné.";
                }
            } else {
                // nemam vsechny atributy
                echo "ERROR: Nebyly přijaty požadované atributy uživatele.";
            }
            echo "<br><br>";
        }

?>
        <h2>Osobní údaje</h2>
        <form action="" method="POST" oninput="x.value=(pas1.value==pas2.value)?'OK':'Nestejná hesla'"
              autocomplete="off"
        >
            <input type="hidden" name="id_uzivatel" value="<?php echo $userData['id_uzivatel']; ?>">
            <table>
                <tr><td>Login:</td><td><?php echo $userData['login']; ?></td></tr>
                <tr><td>Heslo 1:</td><td><input type="password" name="heslo" id="pas1"></td></tr>
                <tr><td>Heslo 2:</td><td><input type="password" name="heslo2" id="pas2"></td></tr>
                <tr><td>Ověření hesla:</td><td><output name="x" for="pas1 pas2"></output></td></tr>
                <tr><td>Jméno:</td><td><input type="text" name="jmeno" value="<?php echo $userData['jmeno']; ?>" required></td></tr>
                <tr><td>E-mail:</td><td><input type="email" name="email" value="<?php echo $userData['email']; ?>" required></td></tr>
                <tr><td>Právo:</td>
                    <td>
                        <select name="pravo">
                            <?php
                            // ziskam vsechna prava
                            $rights = $myDB->getAllRights();
                            // projdu je a vypisu
                            foreach($rights as $r){
                                $selected = ($userData['id_pravo'] == $r['id_pravo']) ? "selected" : "";
                                echo "<option value='$r[id_pravo]' $selected>$r[nazev]</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr><td>Současné heslo:</td><td><input type="password" name="heslo_puvodni" required></td></tr>
            </table>

            <input type="submit" name="potvrzeni" value="Upravit osobní údaje">
        </form>
<?php
    }
    ///////////// KONEC: PRO PRIHLASENE UZIVATELE ///////////////

    // paticka
    ZakladHTML::createFooter();
?>
