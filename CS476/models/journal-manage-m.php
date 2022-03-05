<?php
    require_once '../models/model.php';

    class JournalManageModel extends Model {
        function __construct() {
            parent::__construct();
        }

        function __destruct() {
            parent::__destruct();
        }

        //atempts to add a new contributor to the database 
        //returns false on fail
        //returns true on succees
        function add_contributor($journal_id, $user_name) {
            //get user id
            $query = $this->db->prepare('SELECT user_id FROM CS476_users 
                                        WHERE username = ?');
            $query->bind_param("s", $user_name);
            $query->execute();

            $result = $query->get_result();

            if ($row = $result->fetch_assoc())
            {
                $user_id = $row["user_id"];
            }
            else
            {
                return false;
            }

            $query = $this->db->prepare('INSERT INTO CS476_contributors (journal_id, user_id)
                                        VALUES (?, ?)');
            $query->bind_param("ii", $journal_id, $user_id);
            
            if ($query->execute())
            {
                return true;
            }

            return false;
        }

        //removes a contributor from a journal 
        //returns false on fail
        //returns true on success
        function remove_contributor($journal_id, $user_id) {
            $query = $this->db->prepare('DELETE FROM CS476_contributors
                                        WHERE journal_id = ? AND user_id = ?');
            $query->bind_param("ii", $journal_id, $user_id);
            
            if ($query->execute())
            {
                return true;
            }

            return false;
        }

        //makes a journal read only 
        //returns an empty strin on succees
        //retruns an error string on fail
        function make_read_only($journal_id) {
            $query = $this->db->prepare('UPDATE CS476_journals
                                            SET archived = 1
                                            WHERE journal_id = ?');
            $query->bind_param("i", $journal_id);
            
            if ($query->execute()) 
            {
                return true;
            }
            return false;
        }

        //makes a journal read write
        //returns an empty strin on succees
        //retruns an error string on fail
        function make_read_write($journal_id) {
            $query = $this->db->prepare('UPDATE CS476_journals
                                            SET archived = 0
                                            WHERE journal_id = ?');
            $query->bind_param("i", $journal_id);
            
            if ($query->execute()) 
            {
                return true;
            }
            return false;
        }
    }
?>