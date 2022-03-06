<?php
    /*
        Dustin: I WANT TO REWRITE THIS AS IT IS NOT AS CLEAR AS THE OTHER PAGES INTERFACES.

        This will display the defualt main page if no request is made.

        There are 2 types of request that can be made: ajax_request and log out request
        These requests must use GET

        The log out request should be formated as main.php?log_out=1

        The ajax requests should be formated as "main.php?ajax_request=" followed by the request type and the important data.

        all ajax request will return in json

        The types of ajax requests are as follows: (replace # with the values)
            ajax_request=user
                -this loads user information about the currently loged in user
                -includes user name, city, prov, avatar path, all owned journals id, all contributed journals id
            
            ajax_request=journal&journal_id=#
                -returns the information on a journal corrisponding to the joural_id
                -returns journal info including: id, title, owner id, date created.
                -jouranl_id=(int)
                    - is the id of the journal you wish to load

            ajax_request=journal_page&journal_id=#&date=#
                -this is not realy required is better to make a reques on the journal_page.php
                -returns the information required to diply a journal page
                -returns date, page_id, weather_high, weather_low, conditions
                -will NOT return any entrys this must be done from journal_page.php
                -jouranl_id=(int)
                    - is the id of the journal you wish to load
                - date=(string)
                    - these are the range of dates 
                    - the string must be in form 'YYYY-MM-DD'

            ajax_request=expense&journal_id=#&first_day=#&last_day=#
                -returns the expences that belong to a journal between the given days (inclusive)
                -pass the first and last day of the month to get all expences for that month
                -jouranl_id=(int)
                    - is the id of the journal you wish to load
                - first _day=(string)
                - last_day=(string)
                    - these are the range of dates 
                    - the string must be in form 'YYYY-MM-DD'

            ajax_request=manage&journal_id=#
                -returns the information required by the journal management
                -returns a list of all contributors to the journal
                -jouranl_id=(int)
                    - is the id of the journal you wish to load

            ajax_request=to_do&journal_id=#&first_day=#&last_day=#
                -returns all the reminders that have a due by date between the given values
                -jouranl_id=(int)
                    - is the id of the journal you wish to load
                - first _day=(string)
                - last_day=(string)
                    - these are the range of dates 
                    - the string must be in form 'YYYY-MM-DD'
            
            ajax_request=photo&journal_id=#&first_day=#&last_day=#
                -returns all the photo entries that have dates between the given values
                -jouranl_id=(int)
                    - is the id of the journal you wish to load
                - first _day=(string)
                - last_day=(string)
                    - these are the range of dates 
                    - the string must be in form 'YYYY-MM-DD'

            ajax_request=create_journal&title=#
                -will create a new journal owned by the current user
                -will return a list of all journals owned by the current user
                - title=(string)
                    - the title of the journal that you wish to create
                    - this string must have a length of less then 80
      
    */
    require_once '../validator.php';
    require_once '../controllers/main-c.php';
    require_once '../views/main-v.php';

    $validate = new Validator();

    session_start();
    //if no session go to index.php
    if ($validate->session() === false)
    {
        session_destroy();
        header("Location: ../index.php");
        exit();
    }

    $html_path = "../html/main.html";

    $controller = new MainController($_SESSION["user_id"]);
    $view = new MainView($html_path);

    //if there is a request deal with it and do not redisplay the page
    if (isset($_GET["ajax_request"]))
    {
        //deal with ajax request
        $response = $controller->ajax_request();
        //format responce in view
        $view->create_json($response);
        $view->ajax_response();
        
        exit();
    }

    //if there is a log out request deal with it by redirecting to logout page
    if(isset($_GET["log_out"]) && $_GET["log_out"] == 1)
    {
        $controller->log_out();
    }
    //if there is no request display the page with todays journal-page

    $view->displayPage();
?>