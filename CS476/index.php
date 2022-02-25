<?php
    require_once 'dbinfo.php';

    session_start();

    //if no session go to login screen
    if(!isset($_SESSION["user_id"]))
    {
        session_destroy();
        header("Location: pages/login.php");
        exit();
    }
    
    $uid = $_SESSION["user_id"];

    $db = new mysqli("localhost", $DB_PASS, $DB_PASS, $DB_PASS);
    if ($db->connect_error)
    {
        die($db->connect_error);
    }

    //if user id is not valid or the database shows that they are not logged in go to login screen and destroy session
    $query = "SELECT user_id FROM CS476_users";
    $responce = $db->query($query);
    if(!($row = $responce->fetch_assoc()) && $row["is_logged_in"] == false)
    {
        $db->close();
        session_destroy();
        header("Location: pages/login.php");
        exit();
    }     

?>
