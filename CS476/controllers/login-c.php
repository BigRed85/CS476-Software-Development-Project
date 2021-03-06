<?php
    
    require_once '../controllers/controller.php';
    require_once '../models/login-m.php';

    class LogInController extends Controller {
        
        function __construct() {
            parent::__construct(); 
            $this->model = new LogInModel();
        }

        //validates, prepares, and executes a log in attempt
        //returns an error message if log in fails
        function login() {

            //prepare varribles
            $username = trim($_POST["username"]);
            $password = trim($_POST["password"]);

            //validate
            $isValid = true;
            $errorMsg = "";

            if ($this->validate->username($username) == false)
            {
                $isValid = false;
                $errorMsg = "Invalid username! ";
            }
            if ($this->validate->password($password) == false)
            {
                $isValid = false;
                $errorMsg = $errorMsg . "Invalid password! ";
            }

            if ($isValid == false)
            {
                return $errorMsg;
            }
            
            //call model to make mySQL request
            //if the request fails return error message
            $errorMsg = $this->model->login($username, $password);
            return $errorMsg;
        }

        function signup() {
            //prepare variables
            $email = $_POST["email"];
            $confe = $_POST["confe"];
            $password = $_POST["pass"];
            $confp = $_POST["confp"];
            $username = $_POST["username"];
            $city = $_POST["city"];
            $prov = $_POST["prov"];
            $DOB = $_POST["bday"];

            //validate
            $isValid = true;
            $errorMsg = "";

            $target_dir = $GLOBALS['avatars_directory'] . "$username/";
            $target_file = $target_dir . basename($_FILES["avatar"]["name"]);

            if ($this->validate->username($username) == false || $confe != $email)
            {
                $isValid = false;
                $errorMsg = "Invalid username! ";
            }
            if ($this->validate->password($password) == false || $confp != $password)
            {
                $isValid = false;
                $errorMsg = $errorMsg . "Invalid password! ";
            }
            if ($this->validate->email($email) == false)
            {
                $isValid = false;
                $errorMsg = $errorMsg . "Invalid eamil! ";
            }
            if ($DOB == null || $DOB == "")
            {
                $isValid = false;
                $errorMsg = $errorMsg . "Invalid birth day! ";
            }

            if(isset($_FILES['avatar'])){ //from https://www.codeproject.com/Questions/1274273/Php-image-upload-validation-function
                $errors= array();
                $file_name = $_FILES['avatar']['name'];
                $file_size = $_FILES['avatar']['size'];
                $file_tmp = $_FILES['avatar']['tmp_name'];
                $file_type = $_FILES['avatar']['type'];
                $file_ext=strtolower(end(explode('.',$file_name)));
                
                $expensions= array("jpeg","jpg","png");
                
                if(in_array($file_ext,$expensions)=== false){
                   $errorMsg="extension not allowed, please choose a JPEG or PNG file. ";
                   $isValid = false;
                }
                
                if($file_size > 2097152) {
                   $errorMsg='File size must be excately 2 MB ';
                   $isValid = false;
                }
            }

            

            if (file_exists($target_file))
            {
                $errorMsg = $errorMsg . "Error: File already exits! ";
                $valid = false;
            }


            if ($isValid == false)
            {
                return $errorMsg;
            }

            

            //call model to make mySQL request
            //if the request fails return error message
            $errorMsg = $this->model->signup($username, $password, $email, $DOB, 
                                $target_file, $target_dir, $city, $prov);
            return $errorMsg;
        }

    }
?>