<?php
    require_once '../controllers/controller.php';
    require_once '../models/to-do-list-m.php';

    class ToDoListController extends Controller {
        private $journal_id;

        function __construct($journal_id) {
            parent::__construct();
            $this->model = new ToDoListModel();
            $this->journal_id = $journal_id;
        }

        //add element to the list
        function add_element($to_do_string, $due_date) {
            //check permission
            if ($this->validate->is_owner($_SESSION["user_id"], $this->journal_id) == false
            && $this->validate->is_contributor($_SESSION["user_id"], $this->journal_id) == false)
            {
                return false;
            }
            //add element
            return $this->model->add_element($to_do_string, $due_date, $this->journal_id);
        }

        //remove element from the list
        function remove_element($element_id) {
            //check permission
            if ($this->validate->is_owner($_SESSION["user_id"], $this->journal_id) == false
            && $this->validate->is_contributor($_SESSION["user_id"], $this->journal_id) == false)
            {
                return false;
            }
            //remove element
            return $this->model->remove_element($element_id);
        }

        //mark an element as compleate
        function mark_compleate($element_id) {
            //check permission
            if ($this->validate->is_owner($_SESSION["user_id"], $this->journal_id) == false
            && $this->validate->is_contributor($_SESSION["user_id"], $this->journal_id) == false)
            {
                return false;
            }
            //mark as compleate
            return $this->model->mark_compleate($element_id);
        }

        //mark an element as incompleate
        function mark_incompleate($element_id) {
            //check permisssion
            if ($this->validate->is_owner($_SESSION["user_id"], $this->journal_id) == false
            && $this->validate->is_contributor($_SESSION["user_id"], $this->journal_id) == false)
            {
                return false;
            }
            //mark as incompleate
            return $this->model->mark_incompleate($element_id);

        }
    }
?>