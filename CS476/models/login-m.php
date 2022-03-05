<?php
    require_once '../models/model.php';

    class LogInModel extends Model {

        function __construct() {
            parent::__construct();
        }

        function __destruct() {
            parent::__destruct();
        }

        //execute the log in request from mySQL 
        function logIn($username, $password) {
            //request the log in information from the database.
            //Dustin: from my understanding this will help prevent SQL injections...
            $query = $this->db->prepare('SELECT user_id, username, avatar 
                        FROM CS476_users WHERE username = ? AND password = ?');
            $query->bind_param("ss", $username, $password);
            $query->execute();

            $result = $query->get_result();

            if ($row = $result->fetch_assoc())
            {
                //log in successful
                session_start();
                $_SESSION["user_id"] = $row["user_id"];
                $_SESSION["username"] = $row["username"];
                $_SESSION["avatar"] = $row["avatar"];
                
                $user_id = $row["user_id"];
                $q = "UPDATE CS476_users SET is_logged_in = 1 WHERE user_id = $user_id";
                $this->db->query("$q"); //no need to secure this

                header("Location: main.php"); 
                exit();
            }
            else
            {
                return "The username/password combination was incorrect.";
            }

        }

        function signUp($username, $password, $email, $bday, 
                $avatar = null, $target_dir = null, $city = null, $prov = null) {

            
            //create directory for avatar
            mkdir($target_dir);

            //upload image file to the server
            if ($avatar != null && move_uploaded_file($_FILES["avatar"]["tmp_name"], $avatar) == false)
            {
                return "ERROR: file did not upload!";
            }

            //request sign up from database
            //secure from SQL injection
            $query = $this->db->prepare('INSERT INTO CS476_users (username, email, password, bday, avatar, city, prov)
                            VALUES (?, ?, ?, ?, ?, ?, ?)');
            $query->bind_param("sssssss", $username, $email, $password, $bday, $avatar, $city, $prov);

            if($query->execute())
            {
                //header("Location: login.php");
                exit();
            }
            return "ERROR: " . $this->db->error;
            

        }
    }
?>