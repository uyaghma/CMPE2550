<?php

    error_log ('In DB Util File' );
    // Database connetion

    $mysql_connetion= null;

    // Response Data from DB
    $mysql_response = array();

    //Status string  that will be appended to 

    $mysql_status="";

    // Function to create Database Connetion

    function mySQLConnection()
    {   
        // Grab hold on to connection varaibles first
        global $mysql_connetion, $mysql_response, $mysql_status;

        // Try to connect to DB
        /* mysqli() - it will take 4 parameters
         1. Server
         2. User
         3. User_Password
         4. DB Name
        */                          // SERVER      USER                 PASSWORD  ,         DB NAME
        $mysql_connetion = new mysqli("localhost", "aulakhha_cmpe2550A02","{Tm!4u6r-OV[","aulakhha_cmpe2550A02" ); 

        if($mysql_connetion -> connect_errno)
        {
            error_log("Error while establishing the connection");
        }
        // if we are here, it means connection is successful
        error_log("Connection is successful");

    }

    //CALL your function 
    // For Testing purpose only
    //mySQLConnection();

    // Function to handle all select Query
    function mySelectQuery($myquery)
    {
        error_log("Inside MyselectQuery function");
        // Grab hold on to connection varaibles first
        global $mysql_connetion, $mysql_response, $mysql_status; 

        $results= false;

        //validate your connection
        if($mysql_connetion == null)
        {
            error_log("No active connection");
            $mysql_status = "No active connection";
            return $results;

        }
        else
        {
            // run the query 
            if( !($results = $mysql_connetion->query($myquery)) )
            {
                // Error Handle it here
                error_log("Error ");
                die();
            }
            error_log("Before returning results");
            return $results;
        }
    }
    // function for executing non-select statements
    function myNonSelectQuery($query)
    {
        error_log("Inside MyNonselectQuery function");
        // Grab hold on to connection varaibles first
        global $mysql_connetion, $mysql_response, $mysql_status; 

        //validate your connection
        if($mysql_connetion == null)
        {
            error_log("No active connection");
            $mysql_status = "No active connection";
            echo $mysql_status;

        }
        else
        {
            // query will only return true or false when no result
            // set is retunred

            if( !($mysql_connetion-> query( $query)))
            {  // handling false case

                $mysql_response[]= "Query errro {$mysql_connetion->errno}  : {$mysql_connetion->error}";
                echo json_encode ($mysql_response);
            }
            // Worked fine

            return $mysql_connetion-> affected_rows; // Will return the number of rows affected

        }

    }