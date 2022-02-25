<?php

    require_once '../controllers/controller.php';

    class MainController extends Controller {

        private $contentController;

        function __construct($user_id) {
            load_user($user_id);
        }

        function load_user($user_id) {

        }

        function load_journal() {

        }

        function load_expence() {

        }

        function load_photo_gallery() {

        }

        function load_journal_management() {

        }

        function load_to_do_list() {

        }

        function log_out() {
            header("Location: logout.php");
            exit();
        }
    }

?>
