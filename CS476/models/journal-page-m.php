<?php
//TO-DO: add edits and deletes
    require_once '../models/model.php';

    class JournalPageModel extends Model {
        function __construct() {
            parent::__construct();
        }

        function __destruct() {
            parent::__destruct();
        }

        //gets the journal_id of the given page
        function get_journal_id($page_id) {
            $query = $this->db->prepare('SELECT journal_id FROM CS476_journal_pages 
                                        WHERE page_id = ?');
            $query->bind_param("i", $page_id);
            $query->execute();

            $result = $query->get_result();

            if ($row = $result->fetch_assoc())
            {
                return (int) $row["journal_id"];
            }

            return "Error:" . $this->db->error;
        }

        //request all entrys that belong to page
        function load_page($page_id) {
            $to_return = array();

            //load events 
            $query = $this->db->prepare('SELECT *  
                        FROM CS476_journal_entries
                        JOIN CS476_event_entries
                        ON CS476_journal_entries.entry_id = CS476_event_entries.entry_id
                        AND CS476_journal_entries.page_id = ?');
            $query->bind_param("i", $page_id);
            $query->execute();
            $result = $query->get_result();

            $events = array();
            while($row = $result->fetch_assoc())
            {
                $events[] = $row;
            }
            $to_return['events'] = $events;

            //load notes
            $query = $this->db->prepare('SELECT *  
                        FROM CS476_journal_entries
                        JOIN CS476_note_entries
                        ON CS476_journal_entries.entry_id = CS476_note_entries.entry_id
                        AND CS476_journal_entries.page_id = ?');
            $query->bind_param("i", $page_id);
            $query->execute();
            $result = $query->get_result();

            $notes = array();
            while($row = $result->fetch_assoc())
            {
                $notes[] = $row;
            }
            $to_return['notes'] = $notes;

            //load photos
            $query = $this->db->prepare('SELECT *  
                        FROM CS476_journal_entries
                        JOIN CS476_photo_entries
                        ON CS476_journal_entries.entry_id = CS476_photo_entries.entry_id
                        AND CS476_journal_entries.page_id = ?');
            $query->bind_param("i", $page_id);
            $query->execute();
            $result = $query->get_result();

            $photos = array();
            while($row = $result->fetch_assoc())
            {
                $photos[] = $row;
            }
            $to_return['photos'] = $photos;
            
            return $to_return;

        }

        //add an entry to database and return the entry_id
        //will return an error string if fails
        //will return an int if succeeds 
        function add_entry($page_id, $user_id, $type) {

            //get the current time
            $datum = new DateTime();
            $time = $datum->format('Y-m-d H:i:s');

            //request a new entry
            $query = $this->db->prepare('INSERT INTO CS476_journal_entries (page_id, user_id, entry_type, entry_time)
                            VALUES (?, ?, ?, ?)');
            $query->bind_param("iiss", $page_id, $user_id, $type, $time);
            $query->execute();

            if ($query->get_result() === false)
            {
                return "Error:" . $this->db->error;
            }


            //get entry_id
            $query = $this->db->prepare('SELECT entry_id FROM CS476_journal_entries 
                                        WHERE page_id = ? AND user_id = ? AND entry_time = ?');
            $query->bind_param("iis", $page_id, $user_id, $time);
            $query->execute();

            $result = $query->get_result();

            if ($row = $result->fetch_assoc())
            {
                return (int) $row["entry_id"];
            }

            return "Error:" . $this->db->error;

        }

        //upload photo to server and add photo entry into database
        //returns an empty string if succees 
        //returns an error string if fails
        function add_photo($file_path, $entry_id, $journal_id) {

            if ($file_path == null) 
            {
                return "ERROR: photo did not upload!";
            }

            //upload image file to the server
            if ($file_path != null && move_uploaded_file($_FILES["avatar"]["tmp_name"], $file_path) == false)
            {
                return "ERROR: photo did not upload!";
            }

            //request the database add file to photo list
            $query = $this->db->prepare('INSERT INTO CS476_photo_entries (entry_id, journal_id, photo_path)
                            VALUES (?, ?, ?)');
            $query->bind_param("iis", $entry_id, $journal_id, $file_path);
            $query->execute();

            if($query->get_result() === true)
            {
                return "";
            }
            
            return "ERROR: " . $this->db->error;
            
        }

        //upload a note in raw text to the server and then adds a note entry to the database
        //returns an empty string if succees 
        //returns an error string if fails
        function add_note($note, $entry_id, $user_id) {

            //crate new text file and save to server
            $file_name = "note-" . $user_id . "-" . $entry_id . ".txt";
            $file_path = "../notes/" . $file_name;

            $file = fopen($file_path, "w") or die("unable to open file");
            fwrite($file, $note);
            fclose($file);


            //request the database add file to note list
            $query = $this->db->prepare('INSERT INTO CS476_note_entries (entry_id, note_path)
                            VALUES (?, ?)');
            $query->bind_param("is", $entry_id, $file_path);
            $query->execute();

            if($query->get_result() === true)
            {
                return "";
            }
            
            return "ERROR: " . $this->db->error;
        }

        //upload an event entry to the database
        //returns an empty string if succees 
        //returns an error string if fails
        function add_event($type, $note, $entry_id) {
            //request the database add file to note list
            $query = $this->db->prepare('INSERT INTO CS476_event_entries (entry_id, event_type, event_note)
                            VALUES (?, ?, ?)');
            $query->bind_param("iss", $entry_id, $type, $note);
            $query->execute();

            if($query->get_result() === true)
            {
                return "";
            }
            
            return "ERROR: " . $this->db->error;

        }
    }

?>