<?php
    require_once('dbUtil.php');
    echo "Demo04 - Data Manipulation <br>";

    // Connect to DB by calling function

    mySQLConnection();

    //UPDATE the data with direct query method

    $name= "Har'simran";

    error_log("Before using escapestring function " .$name);

    //Object oriented style 
    //$name = $mysql_connetion -> real_escape_string( strip_tags( trim($name) ));

    //error_log("After using escapestring function " .$name);

    //Procedural style
                                        // Connection object , Variable/value 
    $name = mysqli_real_escape_string(  $mysql_connetion, strip_tags( trim($name)) );

    error_log("After using escapestring function " .$name);

    $query =  "UPDATE Student ";
    $query .=  "set sname= '$name' ";
    $query .=  "where sid = 100 ";

    error_log("Query ". $query);

    $numRows = myNonSelectQuery($query);

    echo "Number of rows Updated are ". $numRows;


    $query =  "Delete from Student ";
    $query .=  "where sid = 100 ";

    $numRows = myNonSelectQuery($query);

    echo "<br>Number of rows Deleted are ". $numRows;