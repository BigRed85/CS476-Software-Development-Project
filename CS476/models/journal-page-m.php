<?php
    require_once '../models/model.php';

    class JournalPageModel extends Model {
        function __construct() {
            parent::__construct();
        }

        function __destruct() {
            parent::__destruct();
        }

        //gets the journal_id of the given page
        function get_journal_info($page_id) {
            $query = $this->db->prepare('SELECT * FROM CS476_journal_pages 
                                        WHERE page_id = ?');
            $query->bind_param("i", $page_id);
            $query->execute();

            $result = $query->get_result();

            if ($row = $result->fetch_assoc())
            {
                echo($row);
                return $row;
            }

            return false;
        }

        function get_contributor_list($journal_id) {
            $query = $this->db->prepare('SELECT user_id FROM CS476_contributors
                                        WHERE journal_id = ?');
            $query->bind_param("i", $journal_id);
            $query->execute();
            $result = $query->get_result();

            $to_return = array();
            while($row = $result->fetch_assoc())
            {
                $to_return = $row;
            }

            return $to_return;
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

        function get_entry_info($entry_id) {
            $query = $this->db->prepare('SELECT *  
                        FROM CS476_journal_entries
                        WHERE entry_id = ?');
            $query->bind_param("i", $entry_id);
            $query->execute();
            $result = $query->get_result();

            if ($row = $result->fetch_assoc())
            {
                return $row;
            }

            return false;
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

            if ( $query->execute())
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
        function add_photo($file_path, $target_dir, $entry_id, $journal_id) {

            if ($file_path == null) 
            {
                return "ERROR: photo did not upload!";
            }

            mkdir($target_dir);

            //upload image file to the server
            if ($file_path != null && move_uploaded_file($_FILES["avatar"]["tmp_name"], $file_path) == false)
            {
                return "ERROR: photo did not upload!";
            }

            //request the database add file to photo list
            $query = $this->db->prepare('INSERT INTO CS476_photo_entries (entry_id, journal_id, photo_path)
                            VALUES (?, ?, ?)');
            $query->bind_param("iis", $entry_id, $journal_id, $file_path);

            if($query->execute())
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

            if($query->execute())
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

            if( $query->execute())
            {
                return "";
            }
            
            return "ERROR: " . $this->db->error;

        }

        //deletes a note entry
        //returns an empty string if successul
        //returns an error string on fail
        function delete_note($entry_id) {
            //get the path to the note file 
            $query = $this->db->prepare('SELECT CS476_note_entries.note_path  
                        FROM CS476_note_entries
                        JOIN CS476_journal_entries
                        ON CS476_journal_entries.entry_id = CS476_note_entries.entry_id
                        AND CS476_journal_entries.entry_id = ?');
            $query->bind_param("i", $entry_id);
            $query->execute();
            $result = $query->get_result();

            if($row = $result->fetch_assoc())
            {
                $note_path = $row["note_path"];
                unlink($notes_path);//delete the note file 
            }

            
            

            //remove the entry from the database
            $query = $this->db->prepare('DELETE CS476_journal_entries, CS476_note_entries
                                        FROM CS476_journal_entries
                                        JOIN CS476_note_entries
                                        ON CS476_journal_entries.entry_id = CS476_note_entries.entry_id
                                        WHERE CS476_journal_entries.entry_id = ?');
            $query->bind_param("i", $entry_id);
            
            if ($query->execute()) 
            {
                return "";
            }
            return "ERROR: " . $this->db->error;


        }

        function delete_event($entry_id) {
            $query = $this->db->prepare('DELETE CS476_journal_entries, CS476_event_entries
                                        FROM CS476_journal_entries
                                        JOIN CS476_event_entries
                                        ON CS476_journal_entries.entry_id = CS476_event_entries.entry_id
                                        WHERE CS476_journal_entries.entry_id = ?');
            $query->bind_param("i", $entry_id);
            
            if ($query->execute()) 
            {
                return "";
            }
            return "ERROR: " . $this->db->error;

        }

        function delete_photo($entry_id) {
            $query = $this->db->prepare('DELETE CS476_journal_entries, CS476_photo_entries
                                        FROM CS476_journal_entries
                                        JOIN CS476_photo_entries
                                        ON CS476_journal_entries.entry_id = CS476_photo_entries.entry_id
                                        WHERE CS476_journal_entries.entry_id = ?');
            $query->bind_param("i", $entry_id);
            
            if ($query->execute()) 
            {
                return "";
            }
            return "ERROR: " . $this->db->error;

        }

        function edit_note($entry_id, $new_note) {
            //get the path to the note
            $query = $this->db->prepare('SELECT CS476_note_entries.note_path  
                        FROM CS476_note_entries
                        JOIN CS476_journal_entries
                        ON CS476_journal_entries.entry_id = CS476_note_entries.entry_id
                        AND CS476_journal_entries.entry_id = ?');
            $query->bind_param("i", $entry_id);
            $query->execute();
            $result = $query->get_result();

            $note_path= "";
            if($row = $result->fetch_assoc())
            {
                $notes_path = $row["note_path"];
            }
            else
            {
                return "Error: " . $this->db->error;
            }

            //update the note
            $file = fopen($notes_path, "w") or die("unable to open file");
            fwrite($file, $new_note);
            fclose($file);
            
            return "";
        }

        function edit_event($entry_id, $new_type, $new_note) {
            $query = $this->db->prepare('UPDATE CS476_journal_entries
                                        JOIN CS476_event_entries
                                        ON CS476_journal_entries.entry_id = CS476_event_entries.entry_id
                                        SET CS476_event_entries.event_type = ?, 
                                        CS476_event_entries.event_note = ?
                                        WHERE CS476_journal_entries.entry_id = ?');
            $query->bind_param("ssi", $new_type, $new_note, $entry_id);
            
            if ($query->execute()) 
            {
                return "";
            }
            return "ERROR: " . $this->db->error;

        }
    }

?>