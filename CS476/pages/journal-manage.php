<?php
    /*
        This will manage the request made by the journal managment page

        if no arguments are passed then the default behavior will present the journal managment page

        all ajax request use the GET method.

        To add a contributor to the journal:

            GET variables:
                add=(string)
                    - if set indicates that you wish to add a contributor
                    - the string contains the user name of the user you wish to add
                journal_id=(int)
                    - the journal id of the journal that you wish to edit

        to remove a contributor from a journal

            GET variables:
                remove=(int)
                    - if set indicates that you wish to remove a contributor
                    - contains the user id of the contributor you wish to remove.
                journal_id=(int)
                    - the journal id of the journal that you wish to edit

        to archive a journal: (makes the journal read-only)

            GET variables:
                archive=1
                    - if is set to 1 this indicats that you wish to archive a journal
                journal_id=(int)
                    - the journal id of the journal that you wish to edit

        to un-archive a jouranl: (makes the jouranl read-write)

            GET variables:
                archive=0
                    - if is set to 0 this indicats that you wish to un-archive a journal
                journal_id=(int)
                    - the journal id of the journal that you wish to edit
    */

    require_once '../validator.php';
    require_once '../controllers/journal-manage-c.php';
    require_once '../views/view.php';

    $validate = new Validator();

    session_start();
    //if no session go to index.php
    if ($validate->session() === false)
    {
        session_destroy();
        header("Location: ../index.php");
        exit();
    }

    $controller = new JournalManageController();
    $view = new View("../html/journal-manage.html");

    $errormsg = "";

    ///add contributor
    if (isset($_GET["add"]))
    {
        if($controller->add_contributor($_GET["journal_id"], $_GET["add"]) == false)
            $errormsg = "ERROR: could not add contributor";
        $view->load_error($errormsg);
        $view->return_error_msg();
        exit();
    }

    if(isset($_GET["remove"]))
    {
        //remove contributor
        if($controller->remove_contributor($_GET["journal_id"], $_GET["remove"]) == false)
            $errormsg = "ERROR: could not remove contributor";
        $view->load_error($errormsg);
        $view->return_error_msg();
        exit();
    }

    if(isset($_GET["archive"]) && $_GET["archive"] == 1)
    {
        if($controller->make_read_only($_GET["journal_id"]) == false)
            $errormsg = "ERROR: could not make read only";
        $view->load_error($errormsg);
        $view->return_error_msg();
        exit();
    }

    if(isset($_GET["archive"]) && $_GET["archive"] == 0)
    {
        if($controller->make_read_write($_GET["journal_id"]) == false)
            $errormsg = "ERROR: could not make read write";
        $view->load_error($errormsg);
        $view->return_error_msg();
        exit();
    }

    $view->displayPage();

?>