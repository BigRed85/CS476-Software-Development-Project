<?php 
    require_once 'validator.php';

    session_start();

    $validate = new Validator();
    
    if ($validate->session() === false)
    {
        session_destroy();
        header("Location: pages/login.php");
        exit();
    }

    header("Location: pages/home.php");

?>
