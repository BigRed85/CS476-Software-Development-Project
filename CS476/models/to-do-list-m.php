<?php
    require_once '../models/model.php';

    class ToDoListModel extends Model {

        function __construct() {
            parent::__construct();
        }

        function __destruct() {
            parent::__destruct();
        }

        //add element to the list
        function add_element($to_do_string, $due_date, $journal_id) {
            $query = $this->db->prepare('INSERT INTO CS476_reminders (journal_id, reminder_note, due_by)
                                        VALUES (?, ?, ?)');
            $query->bind_param("iss", $journal_id, $to_do_string, $due_date);
            
            if ($query->execute())
            {
                return true;
            }
            return false;
        }

        //remove element from the list
        function remove_element($reminder_id) {
            $query = $this->db->prepare('DELETE FROM CS476_reminders
                                        WHERE reminder_id = ?');
            $query->bind_param("i", $reminder_id);
            
            if ($query->execute())
            {
                return true;
            }
            return false;

        }

        //mark an element as compleate
        function mark_compleate($reminder_id) {
            $query = $this->db->prepare('UPDATE CS476_reminders
                                        SET reminder_status = 1
                                        WHERE reminder_id = ?');
            $query->bind_param("i", $reminder_id);
            
            if ($query->execute())
            {
                return true;
            }
            return false;

        }

        //mark an element as incompleate
        function mark_incompleate($element_id) {
            $query = $this->db->prepare('UPDATE CS476_reminders
                                        SET reminder_status = 0
                                        WHERE reminder_id = ?');
            $query->bind_param("i", $reminder_id);
            
            if ($query->execute())
            {
                return true;
            }
            return false;
            
        }
    }
?>