<?php
    require_once '../dbinfo.php';

    session_start();

    $db = new mysqli("localhost", $GLOBALS["DB_NAME"], $GLOBALS["DB_PASS"], $GLOBALS["DB_NAME"]);
    if ($db->connect_error)
    {
        die("Connection fail: " . $db->connect_error);
    }


    //TO-DO: error in log out if already set to 0
    $user_id = $_SESSION["user_id"];
    $q = "UPDATE CS476_users SET is_logged_in = 0 WHERE user_id = $user_id";
    $db->query("$q");

    session_destroy();



    header("Location: login.php");
?>