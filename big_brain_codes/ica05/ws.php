<?
    require_once("dbUtil.php");
    mySQLConnection();

    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
        error_log($id);
        if (!($tresults = mySelectQuery("select ts.title_id, ts.title, ts.type, ts.price from author s join titleauthor t on s.au_id = t.au_id join titles ts on t.title_id = ts.title_id where s.au_id = '$id'"))) {
            echo "Selection query failed";
        }
        else {
            $titles = 0;
            $html = "<table>
            <thead id='headers'>
                <th style='width: 170px;'>Action</th>
                <th id='gap'></th>
                <th>Title ID</th>
                <th id='title-cell'>Title</th>
                <th>Type</th>
                <th>Price</th>
            </thead><tbody>";
            while ($trow = $tresults -> fetch_assoc()) {
                $html .= "<tr>" 
                . "<td style='background-color: var(--dark-accent);'><table style='border-spacing: 3px 0px;'><tr><td class='action buttons delete' id= '" . $trow['title_id'] . "' >Delete</td>" 
                . "<td class='action buttons edit' id='" . $trow['title_id'] . "'>Edit</td></tr></table></td><td id='gap'></td>"
                . "<td>" . $trow['title_id'] . "</td>" 
                . "<td class='title-cell' id='" . $trow['title_id'] . "'>" . $trow['title'] . "</td>" 
                . "<td class='type-cell' id='" . $trow['title_id'] . "'>" . $trow['type'] . "</td>" 
                . "<td class='price-cell' id='" . $trow['title_id'] . "'>$" . $trow['price'] . "</td>"
                . "</tr>";
                $titles++;
            }
            if ($titles == 0) {
                $html = "<p>No titles found for this author.</p>";
            }
        }
        $html .= "</tbody></table>";
        if ($titles > 0) {
            $html .= "<br><p>Retrieved $titles title(s) for this author.</p>";
        }
        echo $html;
    }

    if (isset($_REQUEST['action'])) 
    {
        switch ($_REQUEST['action'])
        {
            case "delete":
                Delete();
                break;
            case "update":
                Update();
                break;
        }
    }

    function Delete() 
    {
        $id = $_REQUEST['id'];

        $query = "DELETE from titles where title_id='";
        $query .= $id . "';";

        mySelectQuery($query);
        echo "select statement: " . $query;
    }

    function Update()
    {
        $id = $_REQUEST['id'];

        $query = "UPDATE titles ";
        $query .= "" . $id;
    }