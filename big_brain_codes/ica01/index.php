<?
    require_once("util.php");
    error_log("ICA01");
    $status = "";
    $output = "";

    //check for inpu conditions, there must be a name and hobby entered in text boxes
    if ((isset($_REQUEST['name']) && strlen($_REQUEST['name']) > 0)
        && (isset($_REQUEST['hobby']) && strlen($_REQUEST['hobby'] > 0))) {
        
        //strip user inputs to sanitize
        $name = strip_tags(trim($_REQUEST['name']));
        $hobby = strip_tags(trim($_REQUEST['hobby']));
        $howmuch = strip_tags(trim($_REQUEST['howmuch']));
        $submit = strip_tags(trim($_REQUEST['submit']));
        $status .= "ProcessForm"; //update status
        $output .= "$name " . str_repeat("really ", $howmuch) . "likes $hobby"; //construct output string
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?
        echo "<link rel='stylesheet' href='style.css'>"; //link the css style sheet to the php file
    ?>
    <title>ICA01</title>
</head>

<body>
    <header>
        <h1>ICA01 - PHP</h1>
    </header>

    <main>
        <div id="server">
            <h2>Part I : Server Info</h2>

            <?php
            echo "Your IP Address is: $_SERVER[REMOTE_ADDR]<br>"; //fetch the server ip
            echo '$_GET' . " Evaluation: " . count($_GET) . ' entry in the $_GET' . "<br>"; //count the number of entries in the get request
            echo '$_POST' . " Evaluation: " . count($_POST) . ' entry in the $_POST' . "<br>"; //count the number of entries in the post request
            $status .= "+ServerInfo"; //update status
            ?>
        </div>
        <div id="form">
            <h2>Part II : Form Processing</h2>
            <p class="label">$_GET Contents: </p>
            <?
            echo "<ul>"; //start unordered list
            foreach ($_GET as $x => $value) {
                echo "<li>[$x] = " . strip_tags($value) . "</li>"; //display the key and value of each item in get request array
            }
            echo "</ul>"; //end the ordered list
            $status.= "+GETData"; //update status
            ?>
        </div>
        <div id="array">
            <h2>Part III : Array Generation</h2>
            <p class="label">Array Generated: </p>
            <?
            $nums = GenerateNumbers(); //invoke generate numbers function from util.php
            $status .= "+GenerateNumbers"; //update status
            MakeList($nums); //create ordered list using the make list function from util.php
            $status .= "+MakeList+ShowArray"; //update status
            ?>
        </div>
        <div id="form2">
            <h2>Part IV : Form Processing</h2>
            <form action="index.php" method="get">
                Name: <input type="text" name="name" id="input-name"><br>
                Hobby: <input type="text" name="hobby" id="input-hobby"><br>
                How much I like it? <input type="range" name="howmuch" id="slider" value="8" min="1" max="13">
                <input type="submit" name="submit" value="Go Now !" id="callWithGet">
            </form>
        </div>
        <div id="display">
            <? 
                echo "<p id='result'>$output</p>"; //display the output of the user entry
            ?>
        </div>
        <div id="status">
            <?
            echo "Status: $status"; //display status
            ?>
        </div>
    </main>

    <footer id="footer">
        <p>&copy; Uyghur Yaghma</p>
        Last Modified: <script>document.write(document.lastModified)</script>            
    </footer>
</body>

</html>