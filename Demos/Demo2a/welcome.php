<?php
    // To start or join a session
    session_start();

    if(!isset($_SESSION['FirstName']))
    {
        $_SESSION['FirstName']= "Simran";
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome Page</title>
    </head>
    <body>
        <h1> Welcome Page Welcome User: <? echo $_SESSION['FirstName'];?> </h1>

        <a href="page1.php">Page 01</a> 
        <br>
        <a href="logout.php">Logout</a>
    </body>
</html>