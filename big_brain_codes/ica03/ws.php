<?
    require_once("dbUtil.php");
    mySQLConnection();

    if (isset($_REQUEST['id'])) {
        $id = strip_tags(trim($_REQUEST['id']));
        error_log($id);
        if (!($tresults = mySelectQuery("select ts.title_id, ts.title, ts.type, ts.price from author s join titleauthor t on s.au_id = t.au_id join titles ts on t.title_id = ts.title_id where s.au_id = '$id'"))) {
            echo "Selection query failed";
        }
        else {
            $titles = 0;
            $html = "<table>
            <thead id='headers'>
                <td>Title ID</td>
                <td id='title-cell'>Title</td>
                <td>Type</td>
                <td>Price</td>
            </thead><tbody>";
            while ($trow = $tresults -> fetch_assoc()) {
                $html .= "<tr>"
                . "<td>" . $trow['title_id'] . "</td><td id='title-cell'>" . $trow['title'] . "</td><td>" . $trow['type'] . "</td><td>$" . $trow['price'] . "</td>"
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