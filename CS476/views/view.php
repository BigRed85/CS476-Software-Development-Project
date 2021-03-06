<?php

    //base class for the view
    //the __construct function must be called from the child classes
    class View {

        protected $html_path;
        protected $response;
        protected $errormsg;

        function __construct($html_path = "") {
            $this->html_path = $html_path;
            $errormsg = "";
        }

        function displayPage() {
            include($this->html_path);
        }

        //format the given assosicative array into JSON
        function create_json($to_format) {
            $this->response = json_encode($to_format);
        }

        //returns a response to an ajax request
        function ajax_response() {
            echo($this->response);
        }

        //loads an error message into the view
        function load_error($errormsg) {
            if ($errormsg === "" || $errormsg === "OK")
            {
                $array["OK"] = $errormsg;
            }
            else
            {
                $array["ERROR"] = $errormsg;
            }
            
            $this->errormsg = json_encode($array);
        }

        //returns the error message to the client
        function return_error_msg() {
            echo($this->errormsg);
        }

    }

?>