<?php
    require_once '../validator.php';
    require_once '../controllers/main-c.php';
    require_once '../views/main-v.php';

    $html_path = "../html/main.html";

    $validate = new Validator();
    $controller = new MainController();
    $view = new MainView($html_path);

    session_start();

    //if no session go to index.php
    if ($validate->session() === false)
    {

        session_destroy();
        header("Location: ../index.php");
        exit();
    }

    echo("session valid");

    
    $view->displayPage();
?>