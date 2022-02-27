<?php
    require_once '../controllers/controller.php'
    require_once '../models/journal-page-m.php'

    class JournalPageController extends Controller {
        function __construct() {
            parent::__construct();
            $this->model = new JournalPageModel();
        }

        //loads all entries that belong to the journal page
        function load_page($page_id) {

        }

        //uploads a photo to the server
        //returns an error mesage if the upload failed 
        //returns an empty string if the upload works
        function add_photo($entry_id, $journal_id, $page_id) {
            //ask model to make a new entry
            $user_id = $_SESSION["user_id"];

            $entry_id = $module->add_entry($page_id, $user_id, 'photo');

            if (gettype($entry_id) == "string")
            {
                return $entry_id; //is an error mesage
            }


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


            if ($isValid == false)
            {
                return $errorMsg;
            }

            //call model to make mySQL request
            //if the request fails return error message
            //if the request succeeds then return empty string
            $errorMsg = $this->model->add_photo($target_file, $entry_id, $journal_id);
            return $errorMsg;
        }

        //uploads a note to the server
        //returns an error mesage if the upload failed 
        //returns an empty string if the upload works
        function add_note($note, $entry_id) {

        }

        //uploads a event to the server
        //returns an error mesage if the upload failed 
        //returns an empty string if the upload works
        function add_event($type, $note, $entry_id) {

        }
    }

?>