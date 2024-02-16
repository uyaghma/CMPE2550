<?
    // Start or join a session
    session_start();

    if(!isset($_SESSION['FirstName']))
    {
        echo "You are not a authorized user to access this page.";
        die();
    }
    else
    {
        $_SESSION['FirstName']= "Harsimran";    
    }
    

    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Page 1</title>
    </head>
    <body>
        <h1> Page 01 Welcome User: <? echo $_SESSION['FirstName'];?> </h1>

        <a href="welcome.php">Welcome</a> 
        <br>
        <a href="logout.php">Logout</a>
    </body>
</html>