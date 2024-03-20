<?
session_start();
require_once ("dbUtil.php");
mySQLConnection();
error_log("Inside ws.php");

switch ($_POST["action"]) {
    case 'register':
        Register();
        break;
    case 'login':
        Login();
        break;
    case 'delete':
        Delete();
        break;
    case 'update':
        Update();
        break;
    case 'add-user':
        AddUser();
        break;
    case 'add-role':
        AddRole();
        break;
    case 'logout':
        Logout();
        break;
}

function Register()
{
    if (isset ($_POST['username']) && isset ($_POST['password'])) {
        $user = strip_tags(trim($_POST['username']));
        $pass = strip_tags(trim($_POST['password']));
        $hashedpass = password_hash($pass, PASSWORD_BCRYPT);

        if (!($results = mySelectQuery("SELECT * FROM userinfo WHERE username='$user'"))) {
            error_log("Selection query failed");
        } else {
            $rows = $results->fetch_assoc();
            if ($rows < 1) {
                $query = "INSERT INTO userinfo (username, pass, role_id) VALUES ('$user', '$hashedpass', 4)";
                myNonSelectQuery($query);
                echo json_encode(['status' => "Successfully registered"]);
            } else {
                echo json_encode(['error' => "User already exists"]);
            }
        }
    }
}

function Login()
{
    error_log("Inside login from postman");
    if (isset ($_POST['username']) && isset ($_POST['password'])) {
        $user = strip_tags(trim($_POST['username']));
        $pass = strip_tags(trim($_POST['password']));

        $query = "SELECT * FROM userinfo where username='$user'";
        error_log($query);
        $rows = 0;

        if (!($results = mySelectQuery($query))) {
            error_log("Selection query failed");
        } else {
            while ($row = $results->fetch_assoc()) {
                $rows++;
                if (password_verify($pass, $row['pass'])) {
                    $_SESSION['username'] = $user;
                    $_SESSION['role'] = $row['role_id'];
                    $redirect = 'https://thor.cnt.sast.ca/~uyaghma1/CMPE2550_Projects/big_brain_codes/lab02/pages/index.php';
                } else {
                    $error = "Incorrect password";
                }
            }
            if ($rows < 1) {
                $dne = "User doesn't exist";
            }
        }
    }
    echo json_encode(['error' => $error, 'redirect' => $redirect, 'dne' => $dne]);
}

function Delete()
{
    if (isset ($_POST['role'])) {
        $role = $_POST['role'];

        $query = "DELETE FROM roles WHERE role_id=$role";
        $uquery = "DELETE FROM userinfo WHERE role_id=$role";
        myNonSelectQuery($query);
        myNonSelectQuery($uquery);

        $output = RetrieveRoles($_SESSION['role']);
    } else {
        $userid = $_POST['id'];

        $query = "DELETE FROM userinfo WHERE user_id=$userid";
        myNonSelectQuery($query);
        $output = RetrieveData($_SESSION['role']);
    }

    echo json_encode(['status' => "delete", 'output' => $output]);
}

function Update()
{
    if (isset ($_POST['role'])) {
        $userid = $_POST['id'];
        $role = $_POST['role'];

        $query = "UPDATE userinfo"
            . " set role_id=$role"
            . " WHERE user_id=$userid";

        error_log($query);

        myNonSelectQuery($query);
        $output = RetrieveData($_SESSION['role']);
        echo json_encode(['output' => $output]);
    }
    error_log("update function");
    echo json_encode(['status' => "Updated "]);
}

function AddUser()
{
    if (isset ($_POST['username']) && isset ($_POST['password'])) {
        $user = strip_tags(trim($_POST['username']));
        $pass = strip_tags(trim($_POST['password']));
        $role = strip_tags(trim($_POST['role']));
        $hashedpass = password_hash($pass, PASSWORD_BCRYPT);

        $query = "INSERT INTO userinfo (username, pass, role_id) VALUES ('$user', '$hashedpass', $role)";
        error_log($query);
        myNonSelectQuery($query);

        $output = RetrieveData($_SESSION['role']);

        echo json_encode(['output' => $output, 'status' => "Successfully added"]);
    }
}

function AddRole()
{
    global $mysql_connection; // Assuming $mysql_connection is your MySQLi connection variable

    if (isset ($_POST['desc']) && isset ($_POST['roleName'])) {
        $desc = strip_tags(trim($_POST['desc']));
        $roleName = strip_tags(trim($_POST['roleName']));

        // Prepare parameters array for the stored procedure call
        $params = array($desc, $roleName);

        // Call the stored procedure to add a role
        $resultArray = callStoredProcedure('AddRoleProcedure', $params);

        // Check if there's any error returned by the stored procedure
        if (!empty ($resultArray) && isset ($resultArray[0]['error'])) {
            // Output the error message
            echo json_encode(['error' => $resultArray[0]['error']]);
            return;
        }

        // If no error, proceed with retrieving roles
        $output = RetrieveRoles($_SESSION['role']);
        echo json_encode(['output' => $output, 'error' => '']);
    }
}

function Redirect($url)
{
    echo json_encode(['redirect' => $url]);
    exit;
}

function RetrieveRoles($role)
{
    $procedureName = 'CheckRoles';
    $params = array();
    $resultArray = callStoredProcedure($procedureName, $params);

    $table = "<table class='table'>";
    $table .= "<thead><th>Action</th><th>ID</th><th>Name</th><th>Description</th></thead><tbody>";

    foreach ($resultArray as $row) {
        if ($row['role_id'] > $role) {
            $table .= "<tr>";
            $table .= "<td class='action' id='" . $row['role_id'] . "'>" . "<a type='button' class='btn btn-primary rounded-pill px-3 delete' rid='" . $row['role_id'] . "'>Delete</a></td>";
            $table .= "<td id='role-id-" . $row['role_id'] . "' class='role-id'>" . $row['role_id'] . "</td>";
            $table .= "<td id='role-name-" . $row['role_id'] . "' class='role-name'>" . $row['name'] . "</td>";
            $table .= "<td id='role-description-" . $row['role_id'] . "' class='role-description'>" . $row['description'] . "</td>";
            $table .= "</tr>";
        }
    }

    $table .= "</tbody></table>";

    return $table;
}

function RetrieveData($role)
{
    global $mysql_connection; // Assuming $mysql_connection is your MySQLi connection variable

    // Call the stored procedure to fetch userinfo data
    $resultArray = callStoredProcedure('GetUserInfo', array());

    // Check if there are any rows returned
    if (empty ($resultArray)) {
        return "No data found.";
    }

    // Initialize the HTML table
    $table = "<table class='table'>
        <thead>";
    if ($role == 1) {
        $table .= "<th colspan='2'>Action</th>";
    }
    $table .= "<th>userID</th>
        <th>Username</th>
        <th>Hashed Password</th>
        <th>Change role</th>
    </thead>
    <tbody class='table-body'>";

    // Iterate through the result array and add rows to the table
    foreach ($resultArray as $row) {
        if ($row['role_id'] != 1) {
            $table .= "<tr>";
            if ($role == 1) {
                $table .= "<td class='delete-cell'><a type='button' class='btn btn-primary rounded-pill px-3 delete' id='" . $row['user_id'] . "'>Delete</a></td>";
                $table .= "<td class='update-cell'><a type='button' class='btn btn-primary rounded-pill px-3 update' id='" . $row['user_id'] . "'>Update</a></td>";
            }
            $table .= "<td class='id-cell'>" . $row['user_id'] . "</td>"
                . "<td class='username-cell' id='" . $row['user_id'] . "'>" . $row['username'] . "</td>"
                . "<td class='pass-cell' id='" . $row['user_id'] . "'>" . $row['pass'] . "</td>"
                . "<td class='role-cell' id='" . $row['user_id'] . "'>";
            $table .= "<select name='roles' class='form-control' id='roles'>";
            // Call the stored procedure to fetch roles data
            $rolesResultArray = callStoredProcedure('CheckRoles', array());
            if (!empty ($rolesResultArray)) {
                foreach ($rolesResultArray as $roleRow) {
                    if ($roleRow['role_id'] >= $role) {
                        if ($roleRow['role_id'] == $row['role_id']) {
                            $table .= "<option value='" . $roleRow['role_id'] . "' selected>" . $roleRow['name'] . "</option>";
                        } else {
                            $table .= "<option value='" . $roleRow['role_id'] . "'>" . $roleRow['name'] . "</option>";
                        }
                    }
                }
            }
            $table .= "</select>";
            $table .= "</td>"
                . "</tr>";
        }
    }
    $table .= "</tbody>
    </table>";

    return $table;
}

function FetchRoles()
{
    // Call the stored procedure to fetch roles data
    $rolesResultArray = callStoredProcedure('CheckRoles', array());

    // Check if there are any rows returned
    if (empty ($rolesResultArray)) {
        return 'Selection query failed';
    } else {
        $addusers = "";
        foreach ($rolesResultArray as $row) {
            if ($row['role_id'] > 1) {
                $addusers .= "<option value='" . $row['role_id'] . "'>" . $row['name'] . '</option>';
            }
        }
        return $addusers;
    }
}

function Logout()
{
    session_unset();
    session_destroy();
    $redirect = 'https://thor.cnt.sast.ca/~uyaghma1/CMPE2550_Projects/big_brain_codes/lab02/pages/login.php';

    echo json_encode(['redirect' => $redirect]);
}