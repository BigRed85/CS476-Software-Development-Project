<?php
    /*
    will serve up a journal page information and deal with any ajax requests to add delete or edit journal entries.

    the page_id must be given to get the page
     get the page_id from main.php?ajax_request=journal_page&journal_id=#&date=#

    to load a new page use: (replace # with an integer)
       jouranl_page.php?page_id=#

    All other ajax request must be done with the POST method

    To add a journal entry:
        
        POST parameters:
            add=('photo', 'note', or 'event')
                - this tells the server that you wish to add an entry
                - this must be set to 'photo', 'note', or 'event' (without the quotes)
            note=(string)
                - this is used when adding a note or event
                - when adding an event the length of the string should be less than 100
                - when adding a note the length of the string may be arbitrarily large 
                    (it will be limited by the max POST size, im not sure what this is yet)
            type=('planted', 'watered', 'weeding', 'fertalized', 'harvested')
                - this is used when adding an event
                - this specifies the type of event that has been added
                - it must be one of 'planted', 'watered', 'weeding', 'fertalized', 'harvested'
                    (without the quotes)  
            page_id=(int)
                - this is the page that the entry will belong to 
                - this must be an integer
        
        NOTE: the photo will be added via the $_FILE

        this should be followed by reloading the journal page to display the new entry.
        
        In the future i might have this return the new entry 
        so that it could be added to the page dynamicly.
        

    To delete a journal entry:

        POST parameters:
            delete=1
                - this tells the server that you wish to delete an entry
                - this must be set to 1
            entry_id=(int)
                - this is tne id of the entry you wish to delete
                - this must be an integer
            page_id=(int)
                - this is the page that the entry belongs to 
                - this must be an integer
            

        if this fails it will return an error string.
        if this succeeds then it will return an empty string.

        will need to reload the page to see updates 
        (or maybe just use the data on the user side to see update)

    To edit a jouranl entry:

        POST parameters:
            edit=(note, or event)
                - this tells the server that you wish to edit an entry
                - must be set to 'note' or 'event' (without the quotes)
            type=('planted', 'watered', 'weeding', 'fertalized', 'harvested')
                - this is used when editing an event 
                - it should be 'planted', 'watered', 'weeding', 'fertalized', or 'harvested'
                    (without the quotes) 
                - this will replace the old values
            note=(string)
                - this is used when editing a note or event
                - when editing an event the length of the string should be less than 100
                - when editing a note the length of the string may be arbitrarily large 
                    (it will be limited by the max POST size, im not sure what this is yet)
                - this will replace the old values
            page_id=(int)
                - this is the page that the entry belongs to 
                - this must be an integer

        if this fails it will return an error string.
        if this succeeds then it will return an empty string.

        will need to reload the journal page to see updates
        (or maybe just use the data on the user side to see update)

    */
    require_once '../validator.php';
    require_once '../controllers/journal-page-c.php';
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

    $controller;
    $view = new View();
    $errormsg;
    
    
    //check for page request:
    if (isset($_GET["page_id"])) 
    {
        $controller = new JournalPageController($_GET["page_id"]);
        $page = $controller->load_page($_GET["page_id"]);
        $view->create_json($page);
        $view->ajax_response(); //return the page information as json
        exit();
    }
    else if(isset($_POST["page_id"]))
    {
        $controller = new JournalPageController($_POST["page_id"]);
    }
    else 
    {
        echo("ERROR: a page id must be given!");
        exit();
    }



    if (isset($_POST["add"]))
    {
        switch ($_POST["add"]) {
            case 'photo':
                $errormsg = $controller->add_photo();
                break;
            case 'note':
                $errormsg = $controller->add_note();
                break;
            case 'event':
                $errormsg = $controller->add_event();
                break;
            default:
                
                break;
        }
    }

    if (isset($_POST["delete"]) && $_POST["delete"])
    {
        $errormsg = $controller->delete_entry($_POST["entry_id"]);
    }

    if (isset($_POST["edit"]))
    {
        $errormsg = $controller->edit_entry($_POST["entry_id"]);
    }


    $view->load_error($errormsg);
    $view->return_error_msg();


?>