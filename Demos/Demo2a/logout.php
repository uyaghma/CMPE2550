<?
    session_start();
    
    //unset()- clean one variable at a time.
    //session_unset()- is going to clear all the session variables

    // session_destroy()- will destroy the session

    session_unset();
    session_destroy();

    echo "Session is clear. You need to login again";


?>