<?php

    class LogInView {
        private $errorL;
        private $errorS;
        private $isSignUp;

        function __construct() {
            $this->errorL = "";
            $this->errorS = "";
            $this->isSignUP = false;
        }

        function setLoginError($errorMsg) {
            $this->errorL = $errorMsg;
        }

        function setSignupError($errorMsg) {
            $this->errorS = $errorMsg;
            $this->isSignUP = true;
        }

        function DisplayPage() {
            $errorL = $this->errorL;
            $errorS = $this->errorS;

            if($this->isSignUp == true)
            {
                $hiddenL = "hidden";
                $hiddenS = "";
            }
            else
            {
                $hiddenL = "";
                $hiddenS = "hidden";
            }

            include("../html/login.html");
        }
    }

    
?>
