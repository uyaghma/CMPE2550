<?  error_log("Inside server.php");
    /*
    isset() function checks wheather or not a variable is set, which means
    it has to be declared and is not null.
    It return trun if the variable exists and is not null, otherwise false
    */
    // Step 1: Validation of inputs
    if( isset($_REQUEST['name']) && strlen($_REQUEST['name'])>0)
    {
        // Step 2: Sanitize your inputs
        /*
        Need to sanitize data received from client side.
        trim()      - clean white spaces from both end of the input
        strip_tags()- strinps a string from HTML, XML and PHP tags.
        */

        //error_log("Before Santizing data name: ". $_REQUEST['name']. " Age: ". $_REQUEST['age'] );
        
        $cleanUserName= strip_tags( trim($_REQUEST['name']) );
        $cleanUserAge= strip_tags( trim($_REQUEST['age']) );

        //error_log("After Santizing data name: ". $cleanUserName. " Age: ". $cleanUserAge );

        //is_numeric() is going to test wheather a string is number or not

        if(!is_numeric( $cleanUserAge))
        {
            echo "Please provide a valid age value";
        }
        else
        {
            echo "Welcome to server.php file. Your form has been submitted";
            echo "<br> Your Name= ". $cleanUserName;
            echo "<br> Your Age= ". $cleanUserAge;
        }
    }
    else
    {
        echo "You can stay out in cold weather!! Make sure to keep yourself warm";
    }
?>