<?php
    require_once '../validator.php';
    require_once '../models/login-m.php';
    require_once '../globals.php';

    //base class for the controller
    //the __construct function must be called from the child classes
    class Controller {
        protected $validate;
        protected $model;

        function __construct() {
            $this->validate = new Validator();
            $this->model = new LogInModel();
        }
    }

?>