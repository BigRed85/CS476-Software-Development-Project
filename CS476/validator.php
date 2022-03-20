<?php
    //this is a module containing a class called a validator. 
    //it will be used by the server side controllers to preform validation on user input. 
    require_once 'globals.php';
    require_once 'dbinfo.php';

    class Validator {

        private $reg_email;
        private $reg_name;
        private $reg_pass;
        private $reg_sl;
        private $reg_journal_title;
        Private $db;
        private $reg_file;

        function __construct($stringLength = 10) {
            $this->reg_email = $GLOBALS["reg_email"];
            $this->reg_pass = $GLOBALS["reg_pass"];
            $this->reg_name = $GLOBALS["reg_name"];
            $this->reg_journal_title = $GLOBALS["reg_journal_title"];
            $this->reg_file = $GLOBALS["reg_file"];

            $this->reg_sl =  "/^[ -~]{1,$stringLength}$/";

            $this->db = new mysqli("localhost", $GLOBALS["DB_NAME"], $GLOBALS["DB_PASS"], $GLOBALS["DB_NAME"]);
            if ($this->db->connect_error)
            {
                die($this->db->connect_error);
            }
        }

        function __destruct() {
            $this->db->close();
        }

        //validates an email address to ensure that it is in the correct format 
        function email($email) {

            $match = preg_match($this->reg_email, $email);
            if ($email == null || $email == "" || $match == false)
            {
                return false;
            }
            return true;

        }

        //validates a password to ensure that it is in the correct format
        function password($password) {
            
            $match = preg_match($this->reg_pass, $password);
            if ($password == null || $password == "" || $match == false)
            {
                return false;
            }
            return true;
        }

        //validates a username to ensure that it is in the correct format
        function username($username) {
            $match = preg_match($this->reg_name, $username);
            if ($username == null || $username == "" || $match == false)
            {
                return false;
            }
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

        //validates a session, this checks that the user is properly loged in
        function session() {
            //if no session go to login screen
            if(isset($_SESSION["user_id"]) == false)
            {
                return false;
            }
            
            $uid = $_SESSION["user_id"];

            //if user id is not valid or the database shows that they are not logged in go to login screen and destroy session
            $query = "SELECT is_logged_in FROM CS476_users WHERE user_id = $uid";
            $responce = $this->db->query($query);
            $row = $responce->fetch_assoc();
            if(isset($row["is_logged_in"]) == false || $row["is_logged_in"] == false)
            {
                return false;
            } 
            
            return true;
        }

        //validates that the given user owns the given journal
        function is_owner($user_id, $journal_id) {
            //get owner of journal
            $query = $this->db->prepare('SELECT user_id FROM CS476_journals 
                                        WHERE journal_id = ?');
            $query->bind_param("i", $journal_id);
            $query->execute();

            $result = $query->get_result();

            $owner_id;
            if ($row = $result->fetch_assoc())
            {
                $owner_id = $row["user_id"];
            }
            else
            {
                return false; //the journal does not exist
            }

            if ($owner_id == $user_id)
            {
                return true;
            }

            return false;
            
        }

        //validates that the given user is a contributor to the given journal
        function is_contributor($user_id, $journal_id) {
            $query = $this->db->prepare('SELECT user_id FROM CS476_contributors
                                        WHERE journal_id = ?');
            $query->bind_param("i", $journal_id);
            $query->execute();

            $result = $query->get_result();

            $owner_id;
            while ($row = $result->fetch_assoc())
            {
                $cont_id = $row["user_id"];
                if ($cont_id == $user_id)
                {
                    return true;
                }
            }
            return false;

        }

        //checks that a journal name is in a valid format
        function journal_title($journal_title) {
            $match = preg_match($this->reg_journal_title, $journal_title);
            if ($journal_title == null || $journal_title == "" || $match == false) 
            {
                return false;
            }
            return true;
        }

        function file_name($file_name) {
            $match = preg_match($this->reg_file, $file_name);
            if ($file_name == null || $file_name == "" || $match == false) 
            {
                return false;
            }
            return true;
        }

        
    }
?>