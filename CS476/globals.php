<?php
    //directory information 
    $photos_directory = "../photos/"; //where user photos will go
    $avatars_directory = "../avatars/"; //where user avatars will go

    //regular expressions
    $reg_email = "/^\w+@\w+\.[a-zA-Z]{2,3}$/";
    $reg_pass = "/^(?=.*[\d])(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+={}:;'<,>.?~-])(?!=.*[ ]).{8,}$/";
        //passwords must have a capital letter, a lowercase letter, a digit, a special char, and no spaces
    $reg_name = "/^(?=.*[\d])(?=.*[a-z])(?=.*[A-Z])(?!.*[!@#$%^&*()_+={}:;'<,>.?~-]).{4,}$/";
        //user names must have a capital letter, a lowercase letter, a digit, and no special char or spaces

?>