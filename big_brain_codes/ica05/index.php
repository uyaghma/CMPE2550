<?
require_once("dbUtil.php");
mySQLConnection();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="Description" content="Enter your description here" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.6.0/cosmo/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <title>Data Insertion</title>
</head>

<body>
    <div class="header">
        <h1 style="text-align: center;">DATA MANIPULATION</h1>
    </div>
    <div class="main">
        <div class="container-sm">
            <table class="table">
                <thead id="headers">
                    <th>Get Books</th>
                    <th>Author ID</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Phone</th>
                </thead>
                <tbody class="table-group-divider">

                    <?
                    if (!($results = mySelectQuery("select * from author order by au_lname"))) {
                        echo "Selection query fails or have no results";
                    } else {
                        $rowID = 0;
                        while ($row = $results->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><a type='button' class='btn btn-primary rounded-pill px-3 retrieve' id='" . $row['au_id'] . "'>Retrieve</a></td>" .
                                "<td>" . $row['au_id'] . "</td><td>" . $row['au_lname'] . "</td>" .
                                "<td>" . $row['au_fname'] . "</td><td>" . $row['phone'] . "</td>";
                            echo "</tr>";
                            $rowID++;
                        }
                        echo "</tbody><caption>Retrieved " . $rowID . " author record(s).</caption></table>";
                    }
                    ?>
        </div>
        <div id="retrieve-container" class="container-sm" style="display: none;">
        </div>
        <div id="insert-container" class="container-sm">
            <div class="form-group row">
                <label for="title-id" class="col-sm-2 col-form-label">Title ID</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="title-id" placeholder="Title ID">
                </div>
            </div>
            <div class="form-group row">
                <label for="title" class="col-sm-2 col-form-label">Title</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="title" placeholder="Title">
                </div>
            </div>
            <div class="form-group row">
                <label for="type" class="col-sm-2 col-form-label">Type</label>
                <div class="col-sm-5">
                    <select class="form-control" id="type">
                        <option value="" selected hidden>Choose a Book Genre</option>
                        <?
                        $tquery = "SELECT DISTINCT type FROM titles";
                        if (!($tresults = mySelectQuery($tquery))) {
                            echo "Selection query failed or have no results";
                        } else {
                            while ($trow = $tresults->fetch_assoc()) {
                                echo "<option value='" . $trow['type'] ."'>" . $trow['type'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="price" class="col-sm-2 col-form-label">Price</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="price" placeholder="Price">
                </div>
            </div>
            <div class="form-group row">
                <label for="authors" class="col-sm-2 col-form-label">Author(s)</label>
                <div class="col-sm-5">
                    <select multiple="multiple" class="form-control" id="author" size="5">
                        <option value="" selected hidden>Choose the Book's Author(s)</option>
                        <?
                        $query = "SELECT au_id, au_lname, au_fname FROM author";
                        if (!($qresults = mySelectQuery($query))) {
                            echo "Selection query failed or have no results";
                        } else {
                            while ($qrow = $qresults->fetch_assoc()) {
                                echo "<option value='" . $qrow['au_id'] ."'>" . $qrow['au_lname'] . ", " . $qrow['au_fname'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10">
                    <a type='button' class='btn btn-primary rounded-pill px-3 add-book'>Add Book</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
</body>

</html>