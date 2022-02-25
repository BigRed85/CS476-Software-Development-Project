<?php
    require_once 'globals.php';
    require_once 'dbinfo.php';

    class Validator {

        private $reg_email;
        private $reg_name;
        private $reg_pass;
        private $reg_sl;

        function __construct($stringLength = 10) {
            $this->reg_email = $GLOBALS["reg_email"];
            $this->reg_pass = $GLOBALS["reg_pass"];
            $this->reg_name = $GLOBALS["reg_name"];

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

        //checks that a string contains only ascii chars and is shorter then a given length
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

        function session() {
            //if no session go to login screen
            if(isset($_SESSION["user_id"]) == false)
            {
                return false;
            }
            
            $uid = $_SESSION["user_id"];

            $db = new mysqli("localhost", $GLOBALS["DB_NAME"], $GLOBALS["DB_PASS"], $GLOBALS["DB_NAME"]);
            if ($db->connect_error)
            {
                die($db->connect_error);
            }

            //if user id is not valid or the database shows that they are not logged in go to login screen and destroy session
            $query = "SELECT is_logged_in FROM CS476_users WHERE user_id = $uid";
            $responce = $db->query($query);
            $row = $responce->fetch_assoc();
            if(isset($row["is_logged_in"]) == false || $row["is_logged_in"] == false)
            {
                $db->close();
                return false;
            } 
            
            return true;
        }
    }
?>