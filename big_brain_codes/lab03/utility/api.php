<?php
error_log("Inside API.php GET:".json_encode($_GET));
error_log("Inside API.php Request:".json_encode($_REQUEST['request']));

require_once 'apiDef.php';

try
{
    $API = new API($_REQUEST['request']);
    error_log("Inside API.PHP after constructor call");
    echo $API->processAPI();  // Point from where echoing the data back to client
    error_log("After returning data back to client inside API.php");
}
catch(Exception $e)
{
    echo json_encode(Array('error' => $e));
}