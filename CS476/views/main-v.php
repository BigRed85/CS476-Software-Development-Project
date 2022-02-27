<?php

    require_once '../views/view.php';

    class MainView extends View {

        function __construct($html_path) {
            parent::__construct($html_path);
        }

        function DisplayPage() {

            include($this->html_path);
        }
    }

?>