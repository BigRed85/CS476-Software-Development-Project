<?php
    class Validator {

        private $reg_email;
        private $reg_name;
        private $reg_pass;
        private $reg_sl;

        function __construct($stringLength = 10) {
            $this->reg_email = "/^\w+@\w+\.[a-zA-Z]{2,3}$/";
            $this->reg_pass = "/^(?=.*[\d])(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+={}:;'<,>.?~-]).{8,}$/";
            $this->reg_name = "/^(?=.*[\d])(?=.*[a-z])(?=.*[A-Z])(?!.*[!@#$%^&*()_+={}:;'<,>.?~-]).{4,}$/";

            $this->reg_sl =  "/^[ -~]{1,$stringLength}$/";
        }


        function email($email) {

            $match = preg_match($this->reg_email, $email);
            if ($email == null || $email == "" || $match == false)
            {
                return false;
            }
            return true;

        }

        function password($password) {
            
            $match = preg_match($this->reg_pass, $password);
            if ($password == null || $password == "" || $match == false)
            {
                return false;
            }
            return true;
        }

        function username($username) {
            $match = preg_match($this->reg_name, $username);
            if ($username == null || $username == "")
            {
                return false;
            }
            return true;
        }

        function image($file) {

            //to do!
            return true;
        }

        function stringLength($toValidate, $stringLength = null) {
            if ($stringLength != null)
            {
                $reg_sl =  "/^[ -~]{1,$stringLenth}$/";
            }

            $match = preg_match($this->reg_sl, $toValidate);
            if ($toValidate == null || $toValidate == "" || $match == false) 
            {
                return false;
            }
            return true;
        }
    }
?>