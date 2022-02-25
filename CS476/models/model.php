<?php
    require_once '../dbinfo.php';

    //base class for the model
    //the __construct and __destruct functions must be called from the child classes
    class Model {
        protected $db;

        function __construct() {
            $this->db = new mysqli("localhost", $GLOBALS["DB_NAME"], $GLOBALS["DB_PASS"], $GLOBALS["DB_NAME"]);
            if ($this->db->connect_error)
            {
                die("Connection fail: " . $this->db->connect_error);
            }
        }

        function __destruct() {
            $this->db->close();
        }
    }

?>