<?php
///////////////////////////////////////////////////////////////////
////////////// Stranka pro registraci uzivatele ////////////////
///////////////////////////////////////////////////////////////////

    // nacteni souboru s funkcemi
    require_once("MyDatabase.class.php");
    $myDB = new MyDatabase();

    // nacteni hlavicky stranky
    require_once("ZakladHTML.class.php");
    ZakladHTML::createHeader("Registrace nového uživatele");

    // zpracovani formulare pro registraci uzivatele
    if(!empty($_POST['potvrzeni'])){
        // mam vsechny pozadovane hodnoty?
        if(!empty($_POST['login']) && !empty($_POST['heslo']) && !empty($_POST['heslo2'])
            && !empty($_POST['jmeno']) && !empty($_POST['email']) && !empty($_POST['pravo'])
            && $_POST['heslo'] == $_POST['heslo2']
        ){
            // pozn.: heslo by melo byt sifrovano
            // napr. password_hash($password, PASSWORD_BCRYPT) pro sifrovani
            // a password_verify($password, $hash) pro kontrolu hesla.

            // mam vsechny atributy - ulozim uzivatele do DB
            $res = $myDB->addNewUser($_POST['login'], $_POST['heslo'], $_POST['jmeno'], $_POST['email'], $_POST['pravo']);
            // byl ulozen?
            if($res){
                echo "OK: Uživatel byl přidán do databáze.";
            } else {
                echo "ERROR: Uložení uživatele se nezdařilo.";
            }
        } else {
            // nemam vsechny atributy
            echo "ERROR: Nebyly přijaty požadované atributy uživatele.";
        }
        echo "<br><br>";
    }
    
    ///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
    if(!$myDB->isUserLogged()){
?>
        <h2>Registrační formulář</h2>
        <form action="" method="POST" oninput="x.value=(pas1.value==pas2.value)?'OK':'Nestejná hesla'">
            <table>
                <tr><td>Login:</td><td><input type="text" name="login" required></td></tr>
                <tr><td>Heslo 1:</td><td><input type="password" name="heslo" id="pas1" required></td></tr>
                <tr><td>Heslo 2:</td><td><input type="password" name="heslo2" id="pas2" required></td></tr>
                <tr><td>Ověření hesla:</td><td><output name="x" for="pas1 pas2"></output></td></tr>
                <tr><td>Jméno:</td><td><input type="text" name="jmeno" required></td></tr>
                <tr><td>E-mail:</td><td><input type="email" name="email" required></td></tr>
                <tr><td>Právo:</td>
                    <td>
                        <select name="pravo">
                            <?php
                                // ziskam vsechna prava
                                $rights = $myDB->getAllRights();
                                // projdu je a vypisu
                                foreach($rights as $r){
                                    echo "<option value='$r[id_pravo]'>$r[nazev]</option>";
                                }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>

            <input type="submit" name="potvrzeni" value="Registrovat">
        </form>
<?php
    ///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////
    } else {
    ///////////// PRO PRIHLASENE UZIVATELE ///////////////
?>
        <div>
            <b>Přihlášený uživatel se nemůže znovu registrovat.</b>
        </div>
<?php
    }
    ///////////// KONEC: PRO PRIHLASENE UZIVATELE ///////////////

    // paticka
    ZakladHTML::createFooter();
?>
