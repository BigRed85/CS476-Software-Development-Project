<?php

    //base class for the view
    //the __construct function must be called from the child classes
    class View {

        protected $html_path;

        function __construct($html_path) {
            $this->html_path = $html_path;
        }

        function displayPage() {
            //virtual
        }
    }

?>