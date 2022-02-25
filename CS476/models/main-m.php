<?php
    require_once '../models/model.php';

    class MainModle extends Model {

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
            $query = $this->db->prepare('SELECT username, avatar, city, prov 
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
            $query = $this->db->prepare('SELECT *  
                        FROM CS476_journals WHERE user_id = ?');
            $query->bind_param("s", $user_id);
            $query->execute();
            $result = $query->get_result();

            $owned_journals = array();
            while($row = $result->fetch_assoc())
            {
                $owned_journals[] = $row;
            }
            $toReturn["owned_journals"] = $owned_journals;

            //request all journals contributed to 
            $query = $this->db->prepare('SELECT *  
                        FROM CS476_journals
                        JOIN CS476_contributors
                        ON CS476_journals.journal_id = CS476_contributors.journal_id
                        AND CS476_contributors.user_id = ?');
            $query->bind_param("i", $user_id);
            $query->execute();
            $result = $query->get_result();

            $journal_contrabutions = array();
            while($row = $result->fetch_assoc())
            {
                $journal_contrabutions[] = $row;
            }
            $toReturn["journal_contrabutions"] = $journal_contrabutions;

            return $toReturn;
        }

        //get a journal by id
        function load_journal($journal_id) {
            $query = $this->db->prepare('SELECT *  
                        FROM CS476_journals WHERE journal_id = ?');
            $query->bind_param("s", $journal_id);
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

        //load the page that belongs to the given journal and has the given date.
        function load_page($date, $journal_id) {

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

        //will crete a new journal with the given title and owned by the given user
        function create_journal($title, $user_id) {
            
            $query = $this->db->prepare('INSERT INTO CS476_journals (title, user_id)
                                    VALUES (? ,?)');
            $query->bind_param("ss", $title, $user_id);
            $query->execute();
            $result = $query->get_result();

            if($result === false)
            {
                return false;
            }

            return true;
        }

        function load_expense($journal_id) {

        }

        function load_photo_gallery($journal_id) {

        }

        function load_managment($journal_id) {

        }

        function load_to_do_list($journal_id) {

        }
    
    }


?>