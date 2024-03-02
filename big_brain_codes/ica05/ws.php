<?
require_once("dbUtil.php");
mySQLConnection();

switch ($_REQUEST['action']) {
    case 'retrieve':
        Retrieve();
        break;
    case 'edit':
        Edit();
        break;
    case 'update':
        Update();
        break;
    case 'cancel':
        Retrieve();
        break;
    case 'delete':
        Delete();
        break;
    case 'insert':
        Insert();
        break;
}

function Retrieve()
{
    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
        error_log($id);
        if (!($tresults = mySelectQuery("select s.au_lname, s.au_fname, ts.title_id, ts.title, ts.type, ts.price from author s join titleauthor t on s.au_id = t.au_id join titles ts on t.title_id = ts.title_id where s.au_id = '$id'"))) {
            echo "Selection query failed";
        } else {
            $titles = 0;
            $html = "<table class='table'>
            <thead id='headers'>
                <th class='ret-buttons'>Action</th>
                <th>Title ID</th>
                <th id='title-cell'>Title</th>
                <th>Type</th>
                <th>Price</th>
            </thead><tbody>";
            while ($trow = $tresults->fetch_assoc()) {
                $html .= "<tr>"
                    . "<td class='ret-buttons'><a type='button' class='btn btn-primary rounded-pill px-3 delete' id='" . $trow['title_id'] . "'>Delete</a>"
                    . "<a type='button' class='btn btn-primary rounded-pill px-3 edit' id='" . $trow['title_id'] . "'>Edit</a></td>"
                    . "<td>" . $trow['title_id'] . "</td>"
                    . "<td class='title-cell' id='" . $trow['title_id'] . "'>" . $trow['title'] . "</td>"
                    . "<td class='type-cell' id='" . $trow['title_id'] . "'>" . $trow['type'] . "</td>"
                    . "<td class='price-cell' id='" . $trow['title_id'] . "'>$" . $trow['price'] . "</td>"
                    . "</tr>";
                $titles++;
                $au_name = $trow['au_fname'] . " " . $trow['au_lname'];
            }
            if ($titles == 0) {
                $html = "<label class='none'>No titles for this author.</label>";
            } else {
                $html .= "</tbody><caption>Retrieved " . $titles . " title(s) for " . $au_name . ".</caption></table>";
            }
        }
        echo $html;
    }
}

function Edit()
{
    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
        error_log($id);
        if (!($tresults = mySelectQuery("select ts.title_id, ts.title, ts.type, ts.price from author s join titleauthor t on s.au_id = t.au_id join titles ts on t.title_id = ts.title_id where s.au_id = '$id'"))) {
            echo "Selection query failed";
        } else {
            $titles = 0;
            $html = "<table class='table table-sm'>
            <thead id='headers'>
                <th class='ret-buttons'>Action</th>
                <th>Title ID</th>
                <th id='title-cell'>Title</th>
                <th>Type</th>
                <th>Price</th>
            </thead><tbody>";
            while ($trow = $tresults->fetch_assoc()) {
                $html .= "<tr>";

                if ($trow['title_id'] === $_REQUEST['t_id']) {
                    $html .= "<td class='ret-buttons'><a type='button' class='btn btn-primary rounded-pill px-3 update' id='" . $trow['title_id'] . "'>Update</a>"
                        . "<a type='button' class='btn btn-primary rounded-pill px-3 cancel' id='" . $trow['title_id'] . "'>Cancel</a></td>"
                        . "<td>" . $trow['title_id'] . "</td>"
                        . "<td class='title-check' id='" . $trow['title_id'] . "'><input class='title-cell' id='" . $trow['title_id'] . "' placeholder='" . $trow['title'] . "' required></td>"
                        . "<td class='type-check' id='" . $trow['title_id'] . "'><select class='form-select type-cell' aria-label='Default select example' id='" . $trow['title_id'] . "' required>
                                    <option selected hidden>" . $trow['type'] . "</option>
                                    <option value='business'>business</option>
                                    <option value='mod_cook'>mod_cook</option>
                                    <option value='popular_comp'>popular_comp</option>
                                    <option value='psychology'>psychology</option>
                                    <option value='trad_cook'>trad_cook</option>
                                    <option value='music'>music</option>
                                 </select></td>"
                        . "<td class='price-check' id='" . $trow['title_id'] . "'><input class='price-cell' id='" . $trow['title_id'] . "'placeholder='$" . $trow['price'] . "' required></td>"
                        . "</tr>";
                } else {
                    $html .= "<td class='ret-buttons'><a type='button' class='btn btn-primary rounded-pill px-3 delete' id='" . $trow['title_id'] . "'>Delete</a>"
                        . "<a type='button' class='btn btn-primary rounded-pill px-3 edit' id='" . $trow['title_id'] . "'>Edit</a></td>"
                        . "<td>" . $trow['title_id'] . "</td>"
                        . "<td class='title-cell' id='" . $trow['title_id'] . "'>" . $trow['title'] . "</td>"
                        . "<td class='type-cell' id='" . $trow['title_id'] . "'>" . $trow['type'] . "</td>"
                        . "<td class='price-cell' id='" . $trow['title_id'] . "'>$" . $trow['price'] . "</td>"
                        . "</tr>";
                }
                $titles++;
            }
            if ($titles == 0) {
                $html = "<label class='none'>No titles for this author.</label>";
            } else {
                $html .= "</tbody><caption>Retrieved " . $titles . " author record(s).</caption></table>";
            }
        }
        echo $html;
    }
}

function Update()
{
    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
        error_log($id);

        $query = "UPDATE titles ";
        $query .= "set title='" . $_REQUEST['title'];
        $query .= "', type='" . $_REQUEST['type'];
        $query .= "', price='" . $_REQUEST['price'];
        $query .= "' where title_id='" . $_REQUEST['t_id'] . "'";

        myNonSelectQuery($query);

        error_log($query);
    }
    Retrieve();
}

function Delete()
{
    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['t_id'];

        $tquery = "DELETE from titles ";
        $tquery .= "where title_id='" . $id . "'";

        $aquery = "DELETE from titleauthor ";
        $aquery .= "where title_id='" . $id . "'";

        myNonSelectQuery($aquery);
        myNonSelectQuery($tquery);
    }
    Retrieve();
}

function Insert()
{
    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
        $title = $_REQUEST['title'];
        $type = $_REQUEST['type'];
        $price = $_REQUEST['price'];
        $author = $_REQUEST['author'];

        $num_au = sizeof($author);
        $royalty = (int)(100 / $num_au);
        $price = number_format((float)$price, 2, ".", "");

        $query = "INSERT INTO titles (title_id, title, type, price) VALUES ";
        $query .= "('$id', '$title', '$type', $price)";
        myNonSelectQuery($query);

        for ($i = 0; $i < $num_au; $i++) {
            $j = $i+1;
            $tquery = "INSERT INTO titleauthor (au_id, title_id, au_ord, royaltyper) VALUES ";
            $tquery .= "('$author[$i]', '$id', $j, $royalty)";
            myNonSelectQuery($tquery);
        }
    }
    Retrieve();
}