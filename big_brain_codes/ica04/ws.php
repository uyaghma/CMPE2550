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
}

function Retrieve()
{
    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
        error_log($id);
        if (!($tresults = mySelectQuery("select ts.title_id, ts.title, ts.type, ts.price from author s join titleauthor t on s.au_id = t.au_id join titles ts on t.title_id = ts.title_id where s.au_id = '$id'"))) {
            echo "Selection query failed";
        } else {
            $titles = 0;
            $html = "<table class='table'>
            <thead id='headers'>
                <th>Action</th>
                <th>Title ID</th>
                <th id='title-cell'>Title</th>
                <th>Type</th>
                <th>Price</th>
            </thead><tbody>";
            while ($trow = $tresults->fetch_assoc()) {
                $html .= "<tr>"
                    . "<td><a type='button' class='btn btn-primary rounded-pill px-3 delete' id='" . $trow['title_id'] . "'>Delete</a>"
                    . "<a type='button' class='btn btn-primary rounded-pill px-3 edit' id='" . $trow['title_id'] . "'>Edit</a></td>"
                    . "<td>" . $trow['title_id'] . "</td>"
                    . "<td class='title-cell' id='" . $trow['title_id'] . "'>" . $trow['title'] . "</td>"
                    . "<td class='type-cell' id='" . $trow['title_id'] . "'>" . $trow['type'] . "</td>"
                    . "<td class='price-cell' id='" . $trow['title_id'] . "'>$" . $trow['price'] . "</td>"
                    . "</tr>";
                $titles++;
            }
            if ($titles == 0) {
                $html = "<caption style='width: 100%;'>No titles for this author.</caption>";
            } else {
                $html .= "</tbody><caption>Retrieved " . $titles . " author record(s).</caption></table>";
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
                <th>Action</th>
                <th>Title ID</th>
                <th id='title-cell'>Title</th>
                <th>Type</th>
                <th>Price</th>
            </thead><tbody>";
            while ($trow = $tresults->fetch_assoc()) {
                $html .= "<tr>";

                if ($trow['title_id'] === $_REQUEST['t_id']) 
                {
                    $html .= "<td><a type='button' class='btn btn-primary rounded-pill px-3 update' id='" . $trow['title_id'] . "'>Update</a>"
                          . "<a type='button' class='btn btn-primary rounded-pill px-3 cancel' id='" . $trow['title_id'] . "'>Cancel</a></td>"
                          . "<td>" . $trow['title_id'] . "</td>"
                          . "<td> <input class='title-cell' id='" . $trow['title_id'] . "' placeholder='" . $trow['title'] . "'></td>"
                          . "<td><select class='form-select type-cell' aria-label='Default select example' id='" . $trow['title_id'] . "'>
                                    <option selected hidden>" . $trow['type'] ."</option>
                                    <option value='business'>business</option>
                                    <option value='mod_cook'>mod_cook</option>
                                    <option value='popular_comp'>popular_comp</option>
                                    <option value='psychology'>psychology</option>
                                    <option value='trad_cook'>trad_cook</option>
                                 </select></td>"
                          . "<td> <input class='price-cell' id='" . $trow['title_id'] . "'placeholder='$" . $trow['price'] . "'></td>"
                          . "</tr>";
                }
                else 
                {
                    $html .= "<td><a type='button' class='btn btn-primary rounded-pill px-3 delete' id='" . $trow['title_id'] . "'>Delete</a>"
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
                $html = "<caption style='width: 100%;'>No titles for this author.</caption>";
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
        // if (!($tresults = myNonSelectQuery("select ts.title_id, ts.title, ts.type, ts.price from author s join titleauthor t on s.au_id = t.au_id join titles ts on t.title_id = ts.title_id where s.au_id = '$id'"))) {
        //     echo "Selection query failed";
        // }
        if (!($tresults = mySelectQuery("select ts.title_id, ts.title, ts.type, ts.price from author s join titleauthor t on s.au_id = t.au_id join titles ts on t.title_id = ts.title_id where s.au_id = '$id'"))) {
            echo "Selection query failed";
        } else {
            $titles = 0;
            $html = "<table class='table'>
            <thead id='headers'>
                <th>Action</th>
                <th>Title ID</th>
                <th id='title-cell'>Title</th>
                <th>Type</th>
                <th>Price</th>
            </thead><tbody>";
            while ($trow = $tresults->fetch_assoc()) {
                $html .= "<tr>";

                if ($trow['title_id'] === $_REQUEST['t_id']) 
                {
                    $html .= "<td><a type='button' class='btn btn-primary rounded-pill px-3 delete' id='" . $trow['title_id'] . "'>Delete</a>"
                          . "<a type='button' class='btn btn-primary rounded-pill px-3 edit' id='" . $trow['title_id'] . "'>Edit</a></td>"
                          . "<td>" . $trow['title_id'] . "</td>"
                          . "<td class='title-cell' id='" . $trow['title_id'] . "'>" . $_REQUEST['title'] . "</td>"
                          . "<td class='type-cell' id='" . $trow['title_id'] . "'>" . $_REQUEST['type'] . "</td>"
                          . "<td class='price-cell' id='" . $trow['title_id'] . "'>$" . $_REQUEST['price'] . "</td>"
                          . "</tr>";
                }
                else 
                {
                    $html .= "<td><a type='button' class='btn btn-primary rounded-pill px-3 delete' id='" . $trow['title_id'] . "'>Delete</a>"
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
                $html = "<caption style='width: 100%;'>No titles for this author.</caption>";
            } else {
                $html .= "</tbody><caption>Retrieved " . $titles . " author record(s).</caption></table>";
            }
        }
        echo $html;
    }
    //Retrieve();
}

function Delete()
{
    Retrieve();
}