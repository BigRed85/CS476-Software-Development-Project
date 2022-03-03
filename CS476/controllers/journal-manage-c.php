<?php
    require_once '../controllers/controller.php';
    require_once '../models/journal-manage-m.php';

    class JournalManageController extends Controller {

        function __construct() {
            parent::__construct();
            $this->model = new JournalManageModel();
        }

        //adds a contributor to a journal
        //returns false on fail
        //returns true on succees
        function add_contributor($journal_id, $user_name) {
            //check if current user own the journal
            if ($this->validate->is_owner($_SESSION["user_id"], $journal_id) == false)
            {
                return false;
            }

            //add the selected user to the contributor list
            return $this->model->add_contributor($journal_id, $user_name);
        }

        //removes a contributor from a journal
        //returns false on fail
        //returns true on succees
        function remove_contributor($journal_id, $user_id) {
            //cheak if user owns the journal
            if ($this->validate->is_owner($_SESSION["user_id"], $journal_id) == false)
            {
                return false;
            }
            
            //remove user from contributor list
            return $this->model->remove_contributor($journal_id, $user_id);
        }

        //makes journal read only
        //returns false on fail
        //returns true on succees
        function make_read_only($journal_id) {
            //check if user owns the journal
            if ($this->validate->is_owner($_SESSION["user_id"], $journal_id) == false)
            {
                return false;
            }

            return $this->model->make_read_only($journal_id);
        }

        //makes a jouranl read write
        //returns false on fail
        //returns true on succees
        function make_read_write($journal_id) {
            //check if the user owns the journal
            if ($this->validate->is_owner($_SESSION["user_id"], $journal_id) == false)
            {
                return false;
            }

            return $this->model->make_read_write($journal_id);
        }
    }

?>
