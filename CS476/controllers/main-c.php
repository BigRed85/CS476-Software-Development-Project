<?php

    require_once '../controllers/controller.php';
    require_once '../controllers/content-c.php';
    require_once '../models/main-m.php';
    

    class MainController extends Controller {

        private $contentController;
        private $user_id;

        function __construct($user_id) {
            $this->user_id = $user_id;
            $this->model = new MainModel();
        }

        function ajax_request() {

            $request = $_POST["ajax_request"];

            switch ($request) {
                case 'user': //this will load the user information for populating the header and the sidebar
                    $user_id = $_SESSION["user_id"];
                    $to_return = load_user($user_id);
                    break;
                case 'journal': //loads a new journal -- not sure how important this is.
                    $journal_id = $_Post["journal_id"];
                    $to_return = load_journal($journal_id);
                    break;
                case 'journal_page':
                    $date = $_POST["date"];
                    $journal_id = $_Post["journal_id"];
                    $to_return = load_journal_page($journal_id, $date);
                    break;
                case 'expense':
                    $journal_id = $_Post["journal_id"];
                    $first = $_Post["first"];
                    $last = $_Post["last"];
                    $to_return = load_expense($journal_id, $first, $last);
                    break;
                case 'manage':
                    $journal_id = $_Post["journal_id"];
                    $to_return = load_journal_management($journal_id);
                    break;
                case 'to_do':
                    $journal_id = $_Post["journal_id"];
                    $first = $_Post["first"];
                    $last = $_Post["last"];
                    $to_return = load_to_do_list($journal_id, $first, $last);
                    break;
                case 'photo':
                    $journal_id = $_Post["journal_id"];
                    $first = $_Post["first"];
                    $last = $_Post["last"];
                    $to_return = load_photo_gallery($journal_id, $first, $last);
                    break;
                case 'create_journal':
                    $title = $_POST["title"];
                    $user_id = $_SESSION["user_id"];
                    $to_return = create_journal($title, $user_id);
                    break;
            }

            return $to_return;

        }

        //loads user infromation from the model 
        //includes user name, city, prov, avatar path, all owned journals, all contributed journals
        function load_user($user_id = null) {
            if ($user_id == null)
            {
                $user_id = $this->user_id;
            }

            $user_info = $model->load_user($user_id);

            return $user_id;
        }

        //loads journal information from the model
        //returns journal info including: id, title, owner id, date created.
        function load_journal($journal_id) {
            $journal = $model->load_journal();
            return $journal;
        }

        function load_journal_page($journal_id, $date) {
            $journal_page = $model->load_journal_page($journal_id, $date);
            return $journal_page;
        }

        //request the model create a new journal in the database and return the full list of owned journals
        function create_journal($title, $user_id) {
            if ($model->create_journal($title, $user_id) === false)
            {
                //error
                echo("Error could not create journal");
                die();
            }

            return $model->load_owned_journals($user_id);
        }

        //loads the information in the default expence sheet
        function load_expense($journal_id, $first_day, $last_day) {
            $to_return = $model->load_expense($journal_id, $first_day, $last_day);
            return $to_return;
        }

        //load the information for the default photogallery
        function load_photo_gallery($journal_id, $first_day, $last_day) {
            $to_return = $model->load_photo_gallery($journal_id, $first_day, $last_day);
            return $to_return;
        }

        //load the information for the default journal managment page (list of contributors)
        function load_journal_management($journal_id) {
            $to_return = $model->load_journal_management();
            return $to_return;
        }

        //load the information for the defualt to-do list in the given time period
        function load_to_do_list($journal_id, $first_day, $last_day) {
            $to_return = $model->load_to_do_list($journal_id, $first_day, $last_day);
            return $to_return;
        }

        function log_out() {
            $model->log_out($_SESSION["user_id"]);
            header("Location: logout.php");
            exit();
        }
    }

?>
