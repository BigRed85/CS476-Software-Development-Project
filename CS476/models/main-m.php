<?php
    require_once '../models/model.php';

    class MainModel extends Model {

        function __construct() {
            parent::__construct();
        }

        function __destruct() {
            parent::__destruct();
        }
        
        //load all the information that the main page needs to know about the user
        //this includes:
        //      all journals owned, all journals contributed to, username, avatar path, and location 
        function load_user($user_id) {
            $toReturn = array(); //will create an array of arrays

            //request user information
            //protect from sql injection
            $query = $this->db->prepare('SELECT user_id, username, avatar, city, prov 
                        FROM CS476_users WHERE user_id = ?');
            $query->bind_param("i", $user_id);
            $query->execute();
            $result = $query->get_result();

            if($row = $result->fetch_assoc())
            {
                $toReturn["user"] = $row;

            }
            else
            {
                echo("Error loading user data.");
                die();
            }

            //request all owned journals
            $toReturn["owned_journals"] = $this->load_owned_journals($user_id);

            //request all journals contributed to 
            $toReturn["journal_contrabutions"] = $this->load_journal_contribute($user_id);

            return $toReturn;
        }

        //get a journal by id
        //returns journal info including: id, title, owner id, date created
        function load_journal($journal_id) {
            $query = $this->db->prepare('SELECT *  
                        FROM CS476_journals WHERE journal_id = ?');
            $query->bind_param("i", $journal_id);
            $query->execute();
            $result = $query->get_result();

            if($row = $result->fetch_assoc())
            {
                return $row;
            }
            else
            {
                return "Error: no such journal";
            }

        }

        function load_owned_journals($user_id) {
            $query = $this->db->prepare('SELECT *  
                        FROM CS476_journals WHERE user_id = ?');
            $query->bind_param("i", $user_id);
            $query->execute();
            $result = $query->get_result();

            $to_return = array();
            while($row = $result->fetch_assoc())
            {
                $to_return[] = $row;
            }
            return $to_return;
        }

        function load_journal_contribute($user_id) {
            $query = $this->db->prepare('SELECT CS476_journals.*  
                        FROM CS476_journals
                        JOIN CS476_contributors
                        ON CS476_journals.journal_id = CS476_contributors.journal_id
                        AND CS476_contributors.user_id = ?');
            $query->bind_param("i", $user_id);
            $query->execute();
            $result = $query->get_result();

            $to_return = array();
            while($row = $result->fetch_assoc())
            {
                $to_return[] = $row;
            }
            return $to_return;
        }

        //load the page that belongs to the given journal and has the given date.
        function load_journal_page($journal_id, $date) {

            $query = $this->db->prepare('SELECT *  
                        FROM CS476_journal_pages WHERE page_date = ? AND journal_id = ?');
            $query->bind_param("si", $date, $user_id);
            $query->execute();
            $result = $query->get_result();

            if($row = $result->fetch_assoc())
            {
                return $row;
            }
            else
            {
                return "error: Does not exist!";
            }

        }

        //will create a new journal with the given title and owned by the given user
        function create_journal($title, $user_id) {
            
            $query = $this->db->prepare('INSERT INTO CS476_journals (title, user_id)
                                    VALUES (? ,?)');
            $query->bind_param("si", $title, $user_id);

            if($query->execute())
            {
                return true;
            }

            return false;
        }

        
        function load_expense($journal_id, $first_day, $last_day) {
            $query = $this->db->prepare('SELECT * FROM CS476_expenses
                                    WHERE journal_id = ?
                                    AND expence_date BETWEEN ? AND ?');
            $query->bind_param("iss", $journal_id, $first_day, $last_day);
            $query->execute();
            $result = $query->get_result();

            $to_return = array();
            while ($row = $result->fetch_assoc())
            {
                $to_return[] = $row;
            }

            return $to_return;
        }

        //load all contributors to the given journal
        function load_journal_management($journal_id) {
            //get the user name from by joining to the contributor list 
            $query = $this->db->prepare('SELECT CS476_users.username, CS476_users.avatar, CS476_users.user_id
                        FROM CS476_users
                        JOIN CS476_contributors
                        ON CS476_users.user_id = CS476_contributors.user_id
                        AND CS476_contributors.journal_id = ?');
            $query->bind_param("i", $journal_id);
            $query->execute();
            $result = $query->get_result();
            
            $to_return = array();
            while ($row = $result->fetch_assoc())
            {
                $to_return[] = $row;
            }

            return $to_return;

        }

        //load all to do list in given time period
        function load_to_do_list($journal_id, $first_day, $last_day) {
            $query = $this->db->prepare('SELECT * FROM CS476_reminders
                                    WHERE journal_id = ?
                                    AND due_by BETWEEN ? AND ?');
            $query->bind_param("iss", $journal_id, $first_day, $last_day);
            $query->execute();
            $result = $query->get_result();

            $to_return = array();
            while ($row = $result->fetch_assoc())
            {
                $to_return[] = $row;
            }

            return $to_return;

        }

        function load_photo_gallery($journal_id, $first_day, $last_day) {
            $query = $this->db->prepare('SELECT * FROM CS476_photo_entries
                                    WHERE journal_id = ?
                                    AND photo_date BETWEEN ? AND ?');
            $query->bind_param("iss", $journal_id, $first_day, $last_day);
            $query->execute();
            $result = $query->get_result();

            $to_return = array();
            while ($row = $result->fetch_assoc())
            {
                $to_return[] = $row;
            }

            return $to_return;
        }

        //sets the user information to loged out in the database
        function log_out($user_id) {
            $q = "UPDATE CS476_users SET is_logged_in = 0 WHERE user_id = $user_id";
            $this->db->query("$q"); //no need to secure this?
        }
    }


?>