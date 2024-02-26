<?  error_log("Inside Demo01b.php");
    $error_message="";
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
           // echo "Please provide a valid age value";
            $error_message=" Please provide a valid age value";
        }
        else
        {
            echo "Welcome to server. Your form has been submitted";
            echo "<br> Your Name= ". $cleanUserName;
            echo "<br> Your Age= ". $cleanUserAge;
        }
    }
    else{
        $error_message=" Please submit with valid data";
    }
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            <?
                echo "Demo01 b: Form processing"
            ?>
        </title>
    </head>
    <body>
        <header>
            <h1> Demo 1b: Form processing</h1>
        </header>
        <main>
            <form action ="Demo01b.php" method="POST">
            <!-- Uncomment the following line to test it with separate server file-->
            <!--<form action ="server.php" method="POST">-->
                What is your name: <input type="text" name="name">
                What is your age? <input type="text" name="age">
                <input type="submit" value="Click me to submit the form">
            </form>
        </main>  
        <footer>
            <?php
                echo $error_message;
            ?>
        </footer> 
    </body>
</html>