<?
    // Single line comment
    # Another way
    /*
        Multiline comment
        Include PHP stuff at the top of the page
    */

    $name = "Simran";
    function UserData() {
        global $name;
        echo "<br> Inside functions your name is $name";
    }
    //Link userdata.php to current file
    //use include or require_once function to include file
    //include('UserData.php'); //can run without the php file loading
    require_once('UserData.php'); //cannot run unless the php file is loaded
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo 1</title>
</head>
<body>
    <header>
        <h1>PHP Intro</h1>
    </header>

    <main>
        <?php
            error_log("First entry");
            echo "This is the first PHP code";
            echo "<br> User Data <br>";
            echo "First Name = $name";
            UserData();
            echo "<br> Age is $age";

            echo "<br> <hr>";
            echo "Print Stars";
            $collection = MakeArray(8);
            echo PrintStars($collection);
        ?>
    </main>

    <footer>
        <?php
            echo "Footer section of my web page";
        ?>
    </footer>
</body>
</html>