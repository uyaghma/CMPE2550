<?php
require_once ("dbUtil.php");
require_once ("ws.php");

class API
{
    private $method = '';
    private $endpoint = '';
    private $verb = '';
    private $args = Array();
    private $file = Null;
    private $cleanedData;

    public function __construct($request)
    {
        header("Content-Type: application/json");

        $this->parseRequest($request);

        $this->method = $_SERVER['REQUEST_METHOD'];     // Retrieve the method used to submit the AJAX request 

        switch($this->method) 
        {
            case 'DELETE':
                parse_str(file_get_contents("php://input"), $this->file);
                $this->cleanedData = $this->_cleanInputs($this->file);
                break;
            case 'POST':
                //parse_str(file_get_contents("php://input"), $this->file);
                $this->cleanedData = $this->_cleanInputs($_POST);
                break;
            case 'GET':
                //parse_str(file_get_contents("php://input"), $this->file);
                $this->cleanedData = $this->_cleanInputs($_GET);
                break;
            case 'PUT':
                parse_str(file_get_contents("php://input"), $this->file);
                $this->cleanedData = $this->_cleanInputs($this->file);
                break;
            default:
                $this->_response('Invalid Method', 405);
                break;
        }
    }

    private function parseRequest($request)
    {
        error_log($request);
        // Remove a trailing '/' if there is one, then separate the URI by '/' chars
        // Store the result in args member
        $this->args = explode('/', rtrim($request, '/'));

        // Remove the the first item of the array and store it as the endpoint
        $this->endpoint = array_shift($this->args);

        // If any items remain and the first is not a numeric value, store it as the verb
        if (array_key_exists(0, $this->args) && !is_numeric($this->args[0])) {
            $this->verb = array_shift($this->args);
        }
    }

    private function _cleanInputs($data)
    {
        $clean_input = Array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->_cleanInputs($v);
            }
        } else {
            $clean_input = trim(strip_tags($data));
        }
        return $clean_input;
    }

    private function FetchMessages()
    {
        switch ($this->method) {
            case 'GET':
                return $this->Get($this->verb);
            case 'POST':
                return $this->Post($this->cleanedData);
            case 'PUT':
                return $this->Put($this->cleanedData, $this->args);
            case 'DELETE':
                return $this->Delete($this->args);
            default:
                return "Invalid Request Type!";
        }
    }

    private function Delete($data)
    {
        $messageID = isset($data[0]) ? $data[0] : '';

        $query = "DELETE FROM messages WHERE message_id=$messageID";
        error_log($query);

        return $this->SQL($query);
    }

    private function Post($data)
    {
        $username = $data['username'];

        $squery = "SELECT * FROM userinfo u JOIN roles r ON u.role_id=r.role_id WHERE username='$username'";

        $results = get_object_vars(get_object_vars(json_decode($this->SQL($squery)))['data'][0]);
        $roleID = $results['role_id'];
        $userID = $results['user_id'];
        $message = isset($data['message']) ? $data['message'] : '';

        $query = "INSERT INTO messages (user_id, message, role) VALUES ($userID, '$message', $roleID)";

        return $this->SQL($query);
    }

    private function Put($data, $id)
    {
        $messageID = isset($id[0]) ? $id[0] : '';
        $message = isset($data['message']) ? $data['message'] : '';

        $query = "UPDATE messages SET message='$message' WHERE message_id=$messageID";

        return $this->SQL($query);
    }

    private function Get($data)
    {
        $filter = isset($data) ? $data : '';

        $query = $filter != "" ? "SELECT * FROM messages m JOIN roles r ON m.role=r.role_id WHERE name like '%$filter%' OR message like '%$filter%' ORDER BY message_id DESC" : "SELECT * FROM messages m JOIN roles r ON m.role=r.role_id ORDER BY message_id DESC";

        error_log($query);
        return $this->SQL($query);
    }

    private function SQL($query)
    {
        error_log("sql function");
        // Establish a database connection
        global $mysql_connection;

        mySQLConnection();

        // Check if connection is successful
        if ($mysql_connection === false) {
            return json_encode(array("error" => "Failed to connect to database"));
        }

        // Execute the query
        $result = mysqli_query($mysql_connection, $query);

        // Check if the query executed successfully
        if ($result === false) {
            // Close the database connection
            mysqli_close($mysql_connection);
            return json_encode(array("error" => "Failed to execute query"));
        }

        // If the query is a DELETE, INSERT, or UPDATE, fetch the updated table
        if (stripos($query, 'DELETE') === 0 || stripos($query, 'INSERT') === 0 || stripos($query, 'UPDATE') === 0) {
            $selectQuery = "SELECT * FROM messages m JOIN roles r ON m.role=r.role_id ORDER BY message_id DESC";
            $selectResult = mysqli_query($mysql_connection, $selectQuery);

            // Check if the SELECT query executed successfully
            if ($selectResult === false) {
                // Close the database connection
                mysqli_close($mysql_connection);
                return json_encode(array("error" => "Failed to fetch updated table"));
            }
            
            $status = (stripos($query, 'DELETE') === 0) ? "Deleted message successfully" : (stripos($query, 'INSERT') === 0 ? "Inserted new message successfully" : (stripos($query, 'UPDATE') === 0 ? "Updated message successfully" : "No success!"));

            // Parse the fetched data
            $data = [];
            while ($row = mysqli_fetch_assoc($selectResult)) {
                $data[] = $row;
            }

            // Close the database connection
            mysqli_close($mysql_connection);

            // Return the updated table as JSON
            return json_encode(["data" => $data, "status" => $status]);
        }

        // If the query is a regular SELECT query, fetch and return the results
        if (stripos($query, 'SELECT') === 0) {
            error_log("inside select query");
            // Parse the fetched data
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }

            $status = "Retrieved messages successfully";

            // Close the database connection
            mysqli_close($mysql_connection);

            // Return the results as JSON
            return json_encode(["data" => $data, "status" => $status]);
        }

        // Close the database connection
        mysqli_close($mysql_connection);

        // Return success message
        return json_encode(array("message" => "Query executed successfully"));
    }

    public function processAPI()
    { // Magic code here
        if (method_exists($this, $this->endpoint))
        {
            error_log("Before calling Example function");
            return $this->_response($this->{$this->endpoint}($this->args));
        }
        return $this->_response("No Endpoint: $this->endpoint", 404);
    }
    
    private function _response($data, $status = 200)
    {
        error_log("Inside Response function");
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        return json_encode($data);
    }

    private function _requestStatus($code)
    {
        error_log("Inside Response Status  function");
        $status = array (  
            200 => 'OK',
            404 => 'Not Found',   
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        ); 
        return ($status[$code])?$status[$code]:$status[500]; 
    }  
}