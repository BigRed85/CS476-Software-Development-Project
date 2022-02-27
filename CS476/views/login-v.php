<?php
    require_once '../views/view.php';

    class LogInView extends View {
        private $errorLogIn;
        private $errorSignUp;
        private $isSignUp;

        function __construct($html_path) {
            parent::__construct($html_path);

            $this->errorLogIn = "";
            $this->errorSignUp = "";
            $this->isSignUP = false;
        }

        function setLoginError($errorMsg) {
            $this->errorLogIn = $errorMsg;
        }

        function setSignupError($errorMsg) {
            $this->errorSignUp = $errorMsg;
            $this->isSignUP = true;
        }

        function displayPage() {
            $errorLogIn = $this->errorLogIn;
            $errorSignUp= $this->errorSignUp;

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

            include($this->html_path);
        }
    }

    
?>
