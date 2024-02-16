<?php
    require_once('dbUtil.php');
    echo "Demo03 - Fetching Data from Database <br>";

    // Connect to DB by calling function

    mySQLConnection();

    // Part 1- Direct query in PHP Code
    // Uncomment this part to test it with direct query
/*
    $st_id= 101; // take this value from client- make sure to perform validation and sanitize your data
    
    $st_name= "sim";
    $query ="select * ";
    $query .= "from Student ";
    // Uncomment the following line to test it for specific student id
    //$query .= "where sid= $st_id ";
    // Uncomment the following line to test it for pattern matching
    $query .= "where sname like '%$st_name%' ";

    error_log($query);


    if( !($results= mySelectQuery($query)) )
    {
        echo "Selection query fails or have no results";
    }
    else
    {
            // we have our results, and can process it
            echo "SID      |   Student Name <br>";
            while($row = $results->fetch_assoc())
            {
                echo $row['sid']. " | " . $row['sname'] ." <br>" ;
            }
    }

*/
    // PART 1- ends here

    //Part 2 - Fetching Data with stored procedure 
    // Uncomment this part to test it with Simple SP
/*

    // Steps:
    // 1. Create a SP in your database
    // 2. Call your SP from PHP code

    echo "Testing it with SP <BR>";
    if( !($results= mySelectQuery( "call getStudentData()")) )
    {
        echo "call to SP fails or have no results";
    }
    else
    {
            // we have our results, and can process it
            echo "SID      |   Student Name <br>";
            while($row = $results->fetch_assoc())
            {
                echo $row['sid']. " | " . $row['sname'] ." <br>" ;
            }
    }

    // PART 2- ends here

*/

    //Part 3 - Fetching Data with stored procedure 
    // Uncomment this part to test it  SP with Parameters


    // Steps:
    // 1. Create a SP in your database
    // 2. Call your SP from PHP code

    $st_id=101;
    $st_name="sim";

    echo "Testing it with SP <BR>";
    // uncomment the following line to test for specific stid
    // Make sure to change your SP as well
    // if ( !($results= mySelectQuery( "call getDataWithParameter($st_id)")) )
    // uncomment the following line to test for pattern matching
    // Make sure to change your SP as well

    // Make sure to use concat('%',st_id,'%') for pattern matching
    if (!($results= mySelectQuery( "call getDataWithParameter('$st_name')")) ) // need single quotes for text values
    {
        echo "call to SP fails or have no results";
    }
    else
    {
        // we have our results, and can process it
        echo "SID      |   Student Name <br>";
        while($row = $results->fetch_assoc())
        {
            echo $row['sid']. " | " . $row['sname'] ." <br>" ;
        }
    }

    // PART 3- ends here