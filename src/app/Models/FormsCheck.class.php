<?php
//////////////////////////////////////////////////////////////
////////////// Vlastni trida pro praci s databazi ////////////////
//////////////////////////////////////////////////////////////

namespace kivweb\Models;

use PDO;
use PDOException;
use PDOStatement;

/**
 * Vlastni trida spravujici formulare.
 */
class FormsCheck {
    /** @var MyDatabase $db  Sprava databaze. */
    private $db;

    /** @var FormsCheck $formscheck  Sprava databaze. */
    private static $formscheck;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        // inicializace prace s DB
        //require_once (DIRECTORY_MODELS ."/MyDatabase.class.php");
        $this->db = MyDatabase::getMyDatabase();
    }

    /**
     * Tovarni metoda pro poskytnuti singletonu FormsCheck
     * @return FormsCheck    instance
     */
    public static function getMyFormsCheck(): FormsCheck
    {
        if(empty(self::$formscheck)){
            self::$formscheck = new FormsCheck();
        }
        return self::$formscheck;
    }

    public function checkLoginLogout(){
        if(isset($_POST['action']) and $_POST['action'] == "login") {
            $islogin = $this->db->userLogin($_POST['username'], $_POST['password']);
            $isuserlogged = $this->db->isUserLogged();
            if($isuserlogged){
                return true;
            }
            else{
                $this->db->userLogout();
                return false;
            }

        }

        if(isset($_POST['action']) and $_POST['action'] == "logout") {

            $this->db->userLogout();
            $isuserlogged = $this->db->isUserLogged();
            if(!$isuserlogged){
                return true;
            }
            else{
                return false;
            }
        }
    }

    public function checkRegistration(){
        if(isset($_POST['action']) and $_POST['action'] == "registration") {
            if(isset($_POST['user_password']) AND isset($_POST['user_passwordcheck'])){
                if($_POST['user_password'] != $_POST['user_passwordcheck']){
                    header('Location: index.php?page=error');
                    exit;
                }

                $username = htmlspecialchars($_POST['user_username']);
                $name = htmlspecialchars($_POST['user_name']);
                $surname = htmlspecialchars($_POST['user_surname']);
                $email = htmlspecialchars($_POST['user_email']);
                $password = htmlspecialchars($_POST['user_password']);

                $isadded = $this->db->addNewUser($username, $name, $surname, $email, $password);

                if($isadded){
                    $this->db->userLogin($username, $password);
                    header('Location: index.php');
                }

            }

        }
    }

    public function checkContribution($page, $id){
        /*if($id != null){
            header('Location: index.php?page='.$page.'&id='.$id);
        }
        else {
            header('Location: index.php?page=' . $page);
        }*/


        //NAHRANI DAT
        if(isset($_POST['action']) and $_POST['action'] == "contribution_submit") {
            $name = htmlspecialchars($_POST['contribution_name']);
            $abstract = htmlspecialchars($_POST['contribution_abstract']);

            $file = $_FILES['contribution_files'];
            if($file['tmp_name']!=null AND $file['name']!=null) {
                $filename = $file['name'];
                $filecontents = file_get_contents($file['tmp_name']);
            }
            else {
                $filename = null;
                $filecontents = null;
            }

            $isadded = false;
            if ($page == 'edit_contribution'){
                if($id != null) {
                    $isadded = $this->db->updateContribution($id,$name, $abstract, $filename, $filecontents);
                }
            }
            else {
                $isadded = $this->db->addContribution($name, $abstract, $filename, $filecontents);
            }

            if(true){
                if($id != null){
                   // header('Location: index.php?page='.$page.'&id='.$id);
                }
                else {
                    $id_contribution = $this->db->getAllContributions();
                    if ($id_contribution!=null){
                        $id = $id_contribution[0]['contributions_id_contributions'];
                        header('Location: index.php?page=edit_contribution&id='.$id);
                    }
                }
            }
        }

        //SMAZANI SOUBORU PRISPEVKU
        if(isset($_POST['action']) and $_POST['action'] == "contributionfile_delete") {
            $id = htmlspecialchars($_POST['contributionfile_id']);

            $this->db->deleteContributionFilebyId($id);

        }

        //SMAZANI PRISPEVKU
        if(isset($_POST['action']) and $_POST['action'] == "contributiondelete_submit") {
            $id = htmlspecialchars($_POST['contributiondelete_id']);
            $this->db->deleteContributionbyId($id);
            header('Location: index.php?page=my_contributions');



        }

    }

    public function checkEditLogin(){
        if(isset($_POST['action']) and $_POST['action'] == "user_submit") {
            if(isset($_POST['user_newpassword']) AND isset($_POST['user_newpasswordcheck']) AND isset($_POST['user_password'])){
                if($_POST['user_newpassword'] != $_POST['user_newpasswordcheck']){
                    header('Location: index.php?page=error&error_text=Zadali%20jste%20rozdílná%20hesla');
                    exit;
                }
                $password = htmlspecialchars($_POST['user_password']);

                $user_data = $this->db->getLoggedUserData();

                if($this->db->checkUserLogin($user_data[0]['users_username'], $password)){
                    header('Location: index.php?page=error');
                    exit;
                }


                $username = htmlspecialchars($_POST['user_username']);
                $name = htmlspecialchars($_POST['user_name']);
                $surname = htmlspecialchars($_POST['user_surname']);
                $email = htmlspecialchars($_POST['user_email']);

                $id_user = $this->db->getLoggedUserId();

                $isadded = $this->db->updateUser($id_user,$username, $name, $surname, $email, $password);

                if($isadded){
                    $this->db->userLogin($username, $password);
                    //header('Location: index.php');
                }
            }

        }

    }

    public function checkReview($page, $id_contributions){
        echo $page;
        echo $id_contributions;
        if(isset($_POST['action']) and $_POST['action'] == "review_submit") {
            $id_review = htmlspecialchars($_POST['review_id']);
            $topic_review = htmlspecialchars($_POST['review_topic']);
            $author_review = htmlspecialchars($_POST['review_author']);
            $abstract_review = htmlspecialchars($_POST['review_abstract']);
            $comment_review = htmlspecialchars($_POST['review_comment']);



            if($page == 'new_review'){
                $isadded = $this->db->addReview($id_review, $id_contributions,$topic_review, $author_review, $abstract_review, $comment_review);
                header('Location: index.php?page=edit_review&id='.$id_contributions);
            }
            else{

                $columns = array(
                    'topic_review',
                    'author_review',
                    'abstract_review',
                    'comment'
                );
                $bindValues = array(
                    $topic_review,
                    $author_review,
                    $abstract_review,
                    $comment_review,
                    $id_review
                );
                $whereStatement = DB_TABLES['reviews'].".id_reviews = ? ";

                $isupdated = $this->db->updateSafe_BindString(DB_TABLES['reviews'], $columns, $whereStatement, $bindValues);
            }

        }

        if(isset($_POST['action']) and $_POST['action'] == "reviewdelete_submit") {
            $id_review = htmlspecialchars($_POST['reviewdelete_id']);
            $this->db->deleteReviewbyId($id_review, $id_contributions);
            header('Location: index.php?page=my_reviews');
        }
    }

    public function checkUserDetail($id_users){
        //USER ROLE
        if(isset($_POST['action']) and $_POST['action'] == "userdetail_role"){
            $role = htmlspecialchars($_POST['user_role']);
            $columns = array(
                'role'
            );

            $bindValues = array(
                $role,
                $id_users
            );

            $wherestatement = DB_TABLES['users'] . ".id_users = ? ";
            $this->db->updateSafe_BindString(DB_TABLES['users'], $columns, $wherestatement, $bindValues);
        }



        //USER BLOCK
        if(isset($_POST['action']) and $_POST['action'] == "userdetail_block"){
            $columns = array(
                'blocked'
            );

            $bindValues = array(
                '1',
                $id_users
            );

            $wherestatement = DB_TABLES['users'] . ".id_users = ? ";
            $this->db->updateSafe_BindString(DB_TABLES['users'], $columns, $wherestatement, $bindValues);
            header('Location: index.php?page=users_management');

        }



        //USER UNBLOCK
        if(isset($_POST['action']) and $_POST['action'] == "userdetail_unblock"){
            $columns = array(
                'blocked'
            );

            $bindValues = array(
                '0',
                $id_users
            );

            $wherestatement = DB_TABLES['users'] . ".id_users = ? ";
            $this->db->updateSafe_BindString(DB_TABLES['users'], $columns, $wherestatement, $bindValues);
            header('Location: index.php?page=users_management');

        }



        //USER DELETE
        if(isset($_POST['action']) and $_POST['action'] == "userdetail_delete"){
            $columns = array(
                'deleted'
            );

            $bindValues = array(
                '1',
                $id_users
            );

            $wherestatement = DB_TABLES['users'] . ".id_users = ? ";
            $this->db->updateSafe_BindString(DB_TABLES['users'], $columns, $wherestatement, $bindValues);
            header('Location: index.php?page=users_management');
            exit();

        }
    }

    public function checkContributionManagement($id_contribution){
        //PUBLISHED
        if(isset($_POST['action']) and $_POST['action'] == "contributiondetail_publish"){

            //Kontrola jestli jsou 3 recenze
            $reviews = $this->db->getReviewsbyIdContribution($id_contribution);
            var_dump($reviews);
            if(count($reviews) < 3){
                $error_text = "Prispevek%20ma%20mene%20nez%20tri%20recenze";
                header('Location: index.php?page=error&error_text='. $error_text);
                exit();
            }

            $columns = array(
                'state'
            );

            $bindValues = array(
                '1',
                $id_contribution
            );

            $wherestatement = DB_TABLES['contributions'] . ".id_contributions = ? ";
            $this->db->updateSafe_BindString(DB_TABLES['contributions'], $columns, $wherestatement, $bindValues);
            //header('Location: index.php?page=reviews_management');

        }


        //DISSMISSED
        if(isset($_POST['action']) and $_POST['action'] == "contributiondetail_dissmiss"){
            $columns = array(
                'state'
            );

            $bindValues = array(
                '2',
                $id_contribution
            );

            $wherestatement = DB_TABLES['contributions'] . ".id_contributions = ? ";
            $this->db->updateSafe_BindString(DB_TABLES['contributions'], $columns, $wherestatement, $bindValues);
            header('Location: index.php?page=reviews_management');

        }
    }

    public function checkReviewAssignment($id_contribution, $page){
        if(isset($_POST['action']) and $_POST['action'] == "reviewAssignement_submit"){
            $reviewer1 = htmlspecialchars($_POST['reviewer_1']);
            $reviewer2 = htmlspecialchars($_POST['reviewer_2']);
            $reviewer3 = htmlspecialchars($_POST['reviewer_3']);


            //NEW REVIEWASSIGNEMENT
            if($page == 'review_assignment') {

                //ReviewAssignment reviewer1 insert
                $insert = array(
                    'id_users' => $reviewer1,
                    'id_contribution' => $id_contribution,
                );
                $this->db->insertSafe_BindString(DB_TABLES['users'], $insert);

                //ReviewAssignment reviewer2 insert
                $insert = array(
                    'id_users' => $reviewer2,
                    'id_contribution' => $id_contribution,
                );
                $this->db->insertSafe_BindString(DB_TABLES['users'], $insert);

                //ReviewAssignment reviewer3 insert
                $insert = array(
                    'id_users' => $reviewer3,
                    'id_contribution' => $id_contribution,
                );
                $this->db->insertSafe_BindString(DB_TABLES['users'], $insert);

            }


            //EDIT REVIEWASSIGNEMENT
            if($page == 'edit_review_assignment') {
                $id_reviewassignement = htmlspecialchars($_POST['reviewAssignement_id']);

                $columns = array(
                    'username',
                    'name',
                    'surname',
                    'email',
                );

                /*$bindValues = array(
                    $username,
                    $name,
                    $surname,
                    $email,
                );
                $bindValues[] = $id_user;*/

                $whereStatement = DB_TABLES['users'].".id_users = ? ";

                $this->updateSafe_BindString(DB_TABLES['users'], $columns, $whereStatement, $bindValues);

                //ReviewAssignment reviewer1 insert
                $insert = array(
                    'id_users' => $reviewer1,
                    'id_contribution' => $id_contribution,
                );
                $this->db->insertSafe_BindString(DB_TABLES['users'], $insert);

                //ReviewAssignment reviewer2 insert
                $insert = array(
                    'id_users' => $reviewer2,
                    'id_contribution' => $id_contribution,
                );
                $this->db->insertSafe_BindString(DB_TABLES['users'], $insert);

                //ReviewAssignment reviewer3 insert
                $insert = array(
                    'id_users' => $reviewer3,
                    'id_contribution' => $id_contribution,
                );
                $this->db->insertSafe_BindString(DB_TABLES['users'], $insert);

            }

        }
    }
}
?>
