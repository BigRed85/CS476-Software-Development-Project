<?php
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