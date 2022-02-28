<?php
//to-do: add edits and deletes

    require_once '../controllers/controller.php'
    require_once '../models/journal-page-m.php'

    class JournalPageController extends Controller {

        private $page_id;
        private $journal_id;
        private $delete_behavior;
        private $edit_behavior;


        function __construct($page_id) {
            parent::__construct();
            $this->model = new JournalPageModel();
            $this->page_id = $page_id;
            $this->journal_id = $this->model->get_journal_id($page_id);
        }

        //loads all entries that belong to the journal page
        function load_page() { 
           return $this->model->load_page($this->page_id);
        }

        //uploads a photo to the server
        //returns an error mesage if the upload failed 
        //returns an empty string if the upload works
        function add_photo($entry_id) {
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

            $target_dir = $GLOBALS['photos_directory'];
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
            $errorMsg = $this->model->add_photo($target_file, $entry_id, $this->journal_id);
            return $errorMsg;
        }

        //deletes a photo
        function delete_photo($photo_id) {
            //delete photo 
            $entry_id = $this->model->delete_photo($photo_id);
            //delete entry
        }

        //uploads a note to the server
        //returns an error mesage if the upload failed 
        //returns an empty string if the upload works
        function add_note($note) {
            //ask model to make a new entry
            $user_id = $_SESSION["user_id"];

            $entry_id = $module->add_entry($this->page_id, $user_id, 'note');

            if (gettype($entry_id) == "string")
            {
                return $entry_id; //is an error mesage
            }

            return $this->model->add_note($note, $entry_id, $user_id);
        }

        function delete_note($note_id) {

        }

        //uploads a event to the server
        //returns an error mesage if the upload failed 
        //returns an empty string if the upload works
        function add_event($type, $note) {

            //ask model to make a new entry
            $user_id = $_SESSION["user_id"];

            $entry_id = $module->add_entry($this->page_id, $user_id, 'event');

            if (gettype($entry_id) == "string")
            {
                return $entry_id; //is an error mesage
            }

            return $this->model->add_event($type, $note);

        }

        function delete_event($event_id) {

        }
    }

?>