<?php
    require_once '../views/view.php';

    class LogInView extends View {
        private $errorLogIn;
        private $errorSignUp;
        private $hiddenL;
        private $hiddenS;

        function __construct($html_path) {
            parent::__construct($html_path);

            $this->errorLogIn = "";
            $this->errorSignUp = "";
            $this->hiddenL = "";
            $this->hiddenS = "hidden";
        }

        function setLoginError($errorMsg) {
            $this->errorLogIn = $errorMsg;
            $this->hiddenL = "";
            $this->hiddenS = "hidden";
        }

        function setSignupError($errorMsg) {
            $this->errorSignUp = $errorMsg;
            $this->hiddenL = "hidden";
            $this->hiddenS = "";
        }

        function displayPage() {
            $errorLogIn = $this->errorLogIn;
            $errorSignUp = $this->errorSignUp;

            $hiddenL = $this->hiddenL;
            $hiddenS = $this->hiddenS;

            include($this->html_path);
        }
    }

    
?>
