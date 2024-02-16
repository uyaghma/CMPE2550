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