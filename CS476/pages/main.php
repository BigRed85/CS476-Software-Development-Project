<?php
    require_once '../validator.php';
    require_once '../controllers/main-c.php';
    require_once '../views/main-v.php';

    $validate = new Validator();

    session_start();
    //if no session go to index.php
    if ($validate->session() === false)
    {
        session_destroy();
        header("Location: ../index.php");
        exit();
    }

    $html_path = "../html/main.html";

    $controller = new MainController($_SESSION["user_id"]);
    $view = new MainView($html_path);

    //if there is a request deal with it and do not redisplay the page
    if (isset($_POST["ajax_request"]))
    {
        //deal with ajax request
        $responce = $controller->ajax_request();
        //format responce in view
        
        echo("Request ");
        echo($_POST["ajax_request"]);
        exit();
    }

    //if there is a log out request deal with it by redirecting to logout page
    if(isset($_POST["log_out"]) && $_POST["log_out"] == 1)
    {
        $controller->log_out();
    }
    //if there is no request deisplay the page with todays journal-page

    echo("session valid");

    $view->displayPage();
?>