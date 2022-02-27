<?php

    //base class for the view
    //the __construct function must be called from the child classes
    class View {

        protected $html_path;
        protected $response;

        function __construct($html_path) {
            $this->html_path = $html_path;
        }

        function displayPage() {
            //virtual
        }

        //format the given assosicative array into JSON
        function create_json($to_format) {
            $this->response = json_encode($to_format);
        }

        //returns a response to an ajax request
        function ajax_response() {
            echo($this->response);
        }

    }

?>