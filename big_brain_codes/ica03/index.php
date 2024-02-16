<? 
    require_once("dbUtil.php");
    mySQLConnection();
    $authors = array();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"/>
    <link rel="stylesheet" href="style.css">
    <title>mySQL and Data Retrieval</title>
</head>
<body>
<div id="site">
        <div id="title">
            <h1 id="header">mySQL and Data Retrieval</h1>
        </div>
        <div class="main">
            <div id="fetch">
                Click to Fetch Authors
            </div>
            <div id="table-container" style="display: none;">
                <table>
                    <thead id="headers">
                        <td>Get Books</td>
                        <td>Author ID</td>
                        <td>Last Name</td>
                        <td>First Name</td>
                        <td>Phone</td>
                    </thead>
                    <tbody>
                    <?
                        if (!($results = mySelectQuery("select * from author order by au_lname"))) {
                            echo "Selection query fails or have no results";
                        }
                        else {
                            $rowID = 0;
                            while ($row = $results -> fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='buttons' id='" . $row['au_id'] . "'>Retrieve</td>" . "<td>" . $row['au_id'] . "</td><td>" . $row['au_lname'] . "</td><td>" . $row['au_fname'] . "</td><td>" . $row['phone'] . "</td>";
                                echo "</tr>";
                                $rowID++;
                            }
                            echo "</tbody></table>";
                        }
                        echo " <br> <p>Retrieved " . $rowID . " author record(s).</p> <hr>";
                    ?>
            </div>
            <div id="retrieve">
            </div>
        </div>
        <div class="area">
            <ul class="circles">
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
                <li><span class="material-symbols-outlined">database</span></li>
            </ul>
        </div>
</body>
</html>