<?php
    /*
    will serve up a journal page information and deal with any ajax requests that it might make. 
    might be a good idea to use jQuery with $.load() for loading a new page.

    the page_id must be given to get the page
     get the page_id from main.php?ajax_request=journal_page&journal_id=#&date=#

    to load a new page use: (replace # with an integer)
       jouranl_page.php?page_id=#

    All other ajax requests should be formated as "main.php?ajax_request=" followed by the request type and the important data.

    All ajax request will return in json

    The types of ajax requests are as follows: (replace # with the values)

    */

    require_once "../controllers/journal-page-c.php"
    require_once "../views/journal-page-v.php"

    $controller = new JournalPageController();

    


    

?>