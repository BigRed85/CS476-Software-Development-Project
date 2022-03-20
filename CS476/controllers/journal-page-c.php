<?php

    require_once '../controllers/controller.php';
    require_once '../models/journal-page-m.php';

    class JournalPageController extends Controller {

        private $date;
        private $page_id;
        private $journal_info;


        function __construct() {
            parent::__construct();
            $this->model = new JournalPageModel();
        }

        //initializes the private variables and checks that the user has permission to the given journal
        // returns false if the journal does not exist or the user does not have access to it
        // returns true if the journal exists and the user has access to it
        function init($date, $journal_id) {
            $this->date = $date;

            $this->journal_info = $this->model->get_journal_info($journal_id);
            if ($this->journal_info === false)
            {
                //journal does not exist exit
                return false;
            }

            if ($this->page_permission() === false)
            {
                return false;
            }

            $this->page_id = $this->model->get_page_id($journal_id, $date);
            return true;
        }

        //loads all entries that belong to the journal page
        //returns an array of values
        function load_page() {
            
            //if has permission to page then load page
            return $this->model->load_page($this->date, $this->journal_info["journal_id"]);
        }

        function page_permission() {
            if ($this->validate->is_owner($_SESSION["user_id"], $this->journal_info["journal_id"]))
            {
                return true;
            }

            return $this->validate->is_contributor($_SESSION["user_id"], $this->journal_info["journal_id"]);
        }

        //determins if the current user can delete the entry
        //returns true if the user has delete permissions 
        //returns false if the user does not have delete permissions
        function delete_permission($entry_id) {
            if ($entry_info = $this->model->get_entry_info($entry_id)) 
            {
                if ($entry_info["user_id"] == $_SESSION["user_id"])
                    return true;
            }

            if ($this->journal_info["user_id"] == $_SESSION["user_id"])
            {
                return true;
            }
            
            return false;

        }

        //determins if the current user can edit the given entry
        //returns true if the user has edit permission
        //returns false if the user does not have edit permisssion
        function edit_permission($entry_id) {
            if ($entry_info = $this->model->get_entry_info($entry_id))
            {
                if ($entry_info["user_id"] == $_SESSION["user_id"])
                    return true;
            }
            return false;
        }


        function add_entry($type, $journal_id, $user_id) {
            //check add permisions
            if ($this->validate->is_owner($user_id, $journal_id) === false && 
                $this->validate->is_contributor($user_id, $journal_id) === false)
            {
                return "ERROR: no permision.";
            }

            //check if page exists
            //if page does not exist then create page
            if ($this->model->is_page($journal_id, $_POST["date"]) === false)
            {
                if ($this->model->create_page($_POST["date"], $journal_id) === false)
                {
                    return "ERROR: could not create page!";
                }
            }

            //get page id
            $page_id = $this->model->get_page_id($_POST["date"], $journal_id);

            //add the apropiate entry
            switch ($type) {
                case 'photo':
                    $errorMsg = $this->add_photo($page_id);
                    break;
                case 'note':
                    $errorMsg = $this->add_note($page_id);
                    break;
                case 'event':
                    $errorMsg = $this->add_event($page_id);
                    break;
                default:
                    return "Must be photo, note, or event";
                    break;
            }

            return $errorMsg; //shooud this return a entry id?
        }

        //delete the given entry
        //will return an empty string if the entry has been deleted
        //will return an error stirng if the entry has not been deleted
        function delete_entry($entry_id) {
            //determin if have delete permission
            if ($this->delete_permission($entry_id) == false)
            {
                return "ERROR: You do not have delete permissions for entry $entry_id";
            }

            //delete the entry
            $entry_info = $this->model->get_entry_info($entry_id);

            if ($entry_info === false)
            {
                return "ERROR: entry $entry_id does not exist!";
            }
            
            switch ($entry_info["entry_type"]) {
                case 'event':
                    return $this->delete_event($entry_id);
                    break;
                
                case 'note':
                    return $this->delete_note($entry_id);
                    break;

                case 'photo':
                    return $this->delete_photo($entry_id);
                    break;
                default:
                    return false;
            }
        }

        function edit_entry($entry_id) {
            //determin if the user has edit permission
            if ($this->edit_permission($entry_id) == false)
            {
                return "ERROR: you do not have edit permissions for $entry_id";
            }

            //edit the entry
            $edit = $_POST["edit"];


            switch ($edit) {
                case 'event':
                    
                    $errorMsg = $this->edit_event($entry_id, $_POST["type"], $_POST["note"]);
                    break;
                case 'note':
                    $errorMsg = $this->edit_note($entry_id, $_POST["note"]);
                    break;
                case 'photo':
                    $error = "Error: cannot edit a photo!"; //there is no edit behavior for photos
                    break;
                default:
                    $error = "Error: must be a event or note!"; 
                    break;
            }

            return $errorMsg;
        }


        //uploads a photo to the server
        //returns an error mesage if the upload failed 
        //returns an empty string if the upload works
        function add_photo($page_id) {
            
            //validate the image file
            $valid = true;
            $errorMsg = "";

            if ($this->validate->file_name($_FILES["photo"]["name"]) === false)
            {
                $errorMsg = $errorMsg . "Error: File namne must not have spaces!";
                $valid = false;
            }

            $target_dir = $GLOBALS['photos_directory'] . "$page_id/";
            $target_file = $target_dir . basename($_FILES["photo"]["name"]);

            $check = getimagesize($_FILES["photo"]["tmp_name"]);
            if($check == false) 
            {
                $valid = false;
                $errorMsg = $errorMsg . "Error: File is not an image. ";
            }

            if (file_exists($target_file))
            {
                
                $errorMsg = $errorMsg . "Error: File already exits! ";
                $valid = false;
            }
            
            //if the image file is not valid return error string
            if ($valid == false)
            {
                return $errorMsg;
            }

            //ask model to make a new entry
            $user_id = $_SESSION["user_id"];

            $entry_id = $this->model->add_entry($page_id, $user_id, 'photo');

            if (gettype($entry_id) == "string")
            {
                return $entry_id; //is an error mesage
            }

            //call model to make mySQL request
            //if the request fails return error message
            //if the request succeeds then return empty string
            $errorMsg = $this->model->add_photo($target_file, $target_dir, $entry_id, $this->journal_info["journal_id"]);
            return $errorMsg;
        }

        //deletes a photo
        function delete_photo($entry_id) {
            return $this->model->delete_photo($entry_id);
        }

        //uploads a note to the server
        //returns an error mesage if the upload failed 
        //returns an empty string if the upload works
        function add_note($page_id) {
            //ask model to make a new entry
            $user_id = $_SESSION["user_id"];
            $note = $_POST["note"];

            $entry_id = $this->model->add_entry($page_id, $user_id, 'note');

            if (gettype($entry_id) == "string")
            {
                return $entry_id; //is an error mesage
            }

            return $this->model->add_note($note, $entry_id, $user_id);
        }

        function delete_note($entry_id) {
            return $this->model->delete_note($entry_id);
        }

        function edit_note($entry_id) {
            //get new note from post
            $new_note = $_POST["note"];

            //ask model to edit
            return $this->model->edit_note($entry_id, $new_note);

        }

        //uploads a event to the server
        //returns an error mesage if the upload failed 
        //returns an empty string if the upload works
        function add_event($page_id) {

            //ask model to make a new entry
            $user_id = $_SESSION["user_id"];
            $type = $_POST["type"];
            $note = $_POST["note"];

            $entry_id = $this->model->add_entry($page_id, $user_id, 'event');

            if (gettype($entry_id) == "string")
            {
                return $entry_id; //is an error mesage
            }

            return $this->model->add_event($type, $note, $entry_id);

        }

        function delete_event($entry_id) {
            return $this->model->delete_event($entry_id);
        }

        function edit_event($entry_id) {
            //get info from the post
            $new_type = $_POST["type"];
            $new_note = $_POST["note"];

            //ask model to edit
            return $this->model->edit_event($entry_id, $new_type, $new_note);
        }
    }

?>