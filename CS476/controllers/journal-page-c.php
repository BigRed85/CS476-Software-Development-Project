<?php

    require_once '../controllers/controller.php';
    require_once '../models/journal-page-m.php';

    class JournalPageController extends Controller {

        private $page_id;
        private $journal_info;


        function __construct($page_id) {
            parent::__construct();
            $this->model = new JournalPageModel();
            $this->page_id = $page_id;

            $journal_info = $this->model->get_journal_info($page_id);
            echo($journal_info);
            if ($journal_info == false)
            {
                echo("Error: could not get assosiated journal!");
                //die(); 
            }

            $this->journal_info = $journal_info;

            if ($this->page_permission() === false)
            {
                echo("ERROR: you do not have access to this journal page");
                //die();
            }
           
        }

        //loads all entries that belong to the journal page
        //returns an array of values
        function load_page() {
            
            //if has permission to page then load page
            return $this->model->load_page($this->page_id);
        }

        function page_permission() {
            if ($this->journal_info["user_id"] == $_SESSION["user_id"])
            {
                return true;
            }

            $cont_list = $this->model->get_contributor_list($journal_info["journal_id"]);

            foreach ($cont_list as $row)
            {
                if ($row["user_id"] == $_SESSION["user_id"])
                {
                    return true;
                }
            } 

            return false;
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

            if ($journal_info["user_id"] == $_SESSION["user_id"])
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
            $type = $_POST["edit"];

            switch ($type) {
                case 'event':
                    
                    $this->edit_event($entry_id, $new_type, $new_note);
                    break;
                case 'note':
                    $this->edit_note($entry_id, $new_note);
                    break;
                case 'photo':
                    return false; //there is no edit behavior for photos
                    break;
                default:
                    return false;
                    break;
            }
        }


        //uploads a photo to the server
        //returns an error mesage if the upload failed 
        //returns an empty string if the upload works
        function add_photo() {
            //ask model to make a new entry
            $user_id = $_SESSION["user_id"];

            $entry_id = $module->add_entry($this->page_id, $user_id, 'photo');

            if (gettype($entry_id) == "string")
            {
                return $entry_id; //is an error mesage
            }


            //validate the image file
            $isValid = true;
            $errorMsg = "";

            $target_dir = $GLOBALS['photos_directory'] . "$user_id/";
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
            if ($isValid == false)
            {
                return $errorMsg;
            }

            //call model to make mySQL request
            //if the request fails return error message
            //if the request succeeds then return empty string
            $errorMsg = $this->model->add_photo($target_file, $target_dir, $entry_id, $this->journal_id);
            return $errorMsg;
        }

        //deletes a photo
        function delete_photo($entry_id) {
            return $this->model->delete_photo($entry_id);
        }

        //uploads a note to the server
        //returns an error mesage if the upload failed 
        //returns an empty string if the upload works
        function add_note($note) {
            //ask model to make a new entry
            $user_id = $_SESSION["user_id"];
            $note = $_POST["note"];

            $entry_id = $module->add_entry($this->page_id, $user_id, 'note');

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
        function add_event() {

            //ask model to make a new entry
            $user_id = $_SESSION["user_id"];
            $type = $_POST["type"];
            $note = $_POST["note"];

            $entry_id = $module->add_entry($this->page_id, $user_id, 'event');

            if (gettype($entry_id) == "string")
            {
                return $entry_id; //is an error mesage
            }

            return $this->model->add_event($type, $note);

        }

        function delete_event($entry_id) {
            return $this->modle->delete_event($entry_id);
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