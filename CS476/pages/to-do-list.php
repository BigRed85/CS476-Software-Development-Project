<?php
    /*
        this script will serve up the to-do list 
        and respond to ajax requests from the to-do list page  

        if no other arguments are passed this will return the to-do-list.html

        all ajax requests should be done with the GET method

        To add an item to the to-do list:

            GET parameters:
                add=(string)
                    - if set indicates that you wish to add an item to the to-do list
                    - the string should contain a description of what needs to be done
                    - no
                due_date=(date)
                    - should contain a date string string in the format "YYYY-MM-DD"
                journal_id=(int)
                    - should contain the id of the journal that the to-do list is assosiated with
                    - this must be set


        To remove an item from the to-do list:

            GET parameters:
                remove=(int)
                    - if set indicates that you wish to remove an item from the to-do list
                    - must be the id of the item that you wish to remove.
                journal_id=(int)
                    - should contain the id of the journal that the to-do list is assosiated with
                    - this must be set


        to mark an item as compleated:

            GET parameters:
                compleate=1
                    - indicates that you wish mark an item as compleate
                reminder_id=(int)
                    - the id of the item that you wish to mark
                journal_id=(int)
                    - should contain the id of the journal that the to-do list is assosiated with
                    - this must be set

        to mark an item as incompleate:

            GET parameters:
                compleate=1
                    - indicates that you wish mark an item as compleate
                reminder_id=(int)
                    - the id of the item that you wish to mark
                journal_id=(int)
                    - should contain the id of the journal that the to-do list is assosiated with
                    - this must be set
    
    */

    require_once '../validator.php';
    require_once '../controllers/to-do-list-c.php';
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

    $view = new View("../html/to-do-list.html");
    
    if (isset($_GET['journal_id']))
    {
        $controller = new ToDoListController($_GET['journal_id']);
        

        //add an element to the to do list
        if (isset($_GET["add"])) 
        {
            if($controller->add_element($_GET["add"], $_GET["due_date"]) === false)
            {
                $view->load_error("ERROR: could not add item to list.");
                $view->return_error_msg(); 
            }
            else
                echo("");
            exit();
        }

        //remove an element from the to do list
        if (isset($_GET["remove"]))
        {
            if($controller->remove_element($_GET["remove"]) === false)
            {
                $view->load_error("ERROR: could not remove item from list.");
                $view->return_error_msg(); 
            }
            else
                echo("");
            exit();
        }

        //mark an element in the to do list as compleate
        if (isset($_GET["compleate"]) && $_GET["compleate"] == 1)
        {
            if($controller->mark_compleate($_GET["reminder_id"]) === false)
            {
                $view->load_error("ERROR: edit the item.");
                $view->return_error_msg(); 
            }
            else
                echo("");
            exit();
        }

        //mark an element in the to do list an incompleate
        if (isset($_GET["compleate"]) && $_GET["compleate"] == 0)
        {
            if($controller->mark_incompleate($_GET["reminder_id"]) === false)
            {
                $view->load_error("ERROR: could not edit the item.");
                $view->return_error_msg(); 
            }
            else
                echo("");
            exit();
        }
    }
    
    $view->displayPage();
?>