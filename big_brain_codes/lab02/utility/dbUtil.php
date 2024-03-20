<?
    $mysql_connection = null;

    $mysql_response = array();

    $mysql_status = "";

function mySQLConnection()
{   
    // Grab hold on to connection varaibles first
    global $mysql_connection, $mysql_response, $mysql_status;

    $mysql_connection = new mysqli("localhost", "uyaghma1_200529845","U2\$p2e5#Mjt,","uyaghma1_cmpe2550A02"); 

    if($mysql_connection -> connect_errno)
    {
        error_log("Error while establishing the connection");
    }
    // if we are here, it means connection is successful
    error_log("Connection is successful");
}

function mySelectQuery($myquery)
{
    error_log("Inside MyselectQuery function");

    global $mysql_connection, $mysql_response, $mysql_status; 

    $results = false;

    if($mysql_connection == null)
    {
        error_log("No active connection");
        $mysql_status = "No active connection";
        return $results;

    }
    else
    {
        if(!($results = $mysql_connection -> query($myquery)))
        {
            error_log("Error ");
            die();
        }
        error_log("Before returning results");
        return $results;
    }
}

function myNonSelectQuery($query)
{
    error_log("Inside MyNonselectQuery function");
    // Grab hold on to connection varaibles first
    global $mysql_connection, $mysql_response, $mysql_status; 

    //validate your connection
    if($mysql_connection == null)
    {
        error_log("No active connection");
        $mysql_status = "No active connection";
        echo $mysql_status;
    }
    else
    {
        if( !($mysql_connection-> query($query)))
        {  // handling false case

            $mysql_response[]= "Query error {$mysql_connection->errno}  : {$mysql_connection->error}";
            echo json_encode ($mysql_response);
        }
        return $mysql_connection -> affected_rows;
    }

}

function callStoredProcedure($procedureName, $params) {
    global $mysql_connection; // Assuming $mysql_connection is your MySQLi connection variable

    // Build the parameter placeholders for the prepared statement
    $paramPlaceholders = implode(',', array_fill(0, count($params), '?'));

    // Prepare the call to the stored procedure
    $stmt = $mysql_connection->prepare("CALL $procedureName($paramPlaceholders)");

    // Bind parameters if there are any
    if (!empty($params)) {
        $types = '';
        $values = array();
        foreach ($params as $param) {
            // Determine the type of the parameter
            if (is_int($param)) {
                $types .= 'i'; // Integer
            } elseif (is_double($param)) {
                $types .= 'd'; // Double
            } elseif (is_string($param)) {
                $types .= 's'; // String
            } else {
                $types .= 's'; // Default to string
            }
            $values[] = $param;
        }

        // Bind parameters dynamically based on their types
        $stmt->bind_param($types, ...$values);
    }

    // Execute the stored procedure
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Fetch results if available
    $resultArray = array();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $resultArray[] = $row;
        }
    }

    // Close the statement
    $stmt->close();

    // Return the result array
    return $resultArray;
}