<?php
    /*
        this script will display the log in screen and deal with any log in or sign up requests

        both the log in and the sign up must be done with a POST

        Variables for a log in:
                login=1
                username=###
                password=###

    */
    require_once '../controllers/login-c.php';
    require_once '../views/login-v.php';

    $error = "";
    $html = "../html/login.html";

    $controller = new LogInController();
    $view = new LogInView($html);

    if(isset($_POST["signup"]) && $_POST["signup"])
    {
        $error = $controller->signup();
        $view->setSignupError($error);
    }
    else if (isset($_POST["login"]) && $_POST["login"])
    {
        $error = $controller->login();
        $view->setLoginError($error);
    }

    $view->displayPage();

?>