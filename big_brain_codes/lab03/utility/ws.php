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
    case 'getinfo':
        GetInfo();
        break;
}

function GetInfo() 
{
    $username = $_POST['username'];
    $results = mySelectQuery("SELECT * FROM userinfo u JOIN roles r ON u.role_id=r.role_id WHERE username='$username'");
    $row = $results -> fetch_assoc();

    error_log(print_r($row,true));
    echo json_encode(['role' => $row['role_id']]);
}

function Register()
{
    if (isset ($_POST['username']) && isset ($_POST['password'])) {
        $user = strip_tags(trim($_POST['username']));
        $pass = strip_tags(trim($_POST['password']));
        $hashedpass = password_hash($pass, PASSWORD_BCRYPT);

        $resultArray = callStoredProcedure('Register', array($user, $hashedpass));

        if (!empty ($resultArray) && isset ($resultArray[0]['error_message'])) {
            echo json_encode(['error' => $resultArray[0]['error_message']]);
        } 
        else {
            echo json_encode(['status' => "Successfully registered, you can now login"]);
        }
    }
}

function Login()
{
    global $USERNAME;
    if (isset ($_POST['username']) && isset ($_POST['password'])) {
        $user = strip_tags(trim($_POST['username']));
        $pass = strip_tags(trim($_POST['password']));

        $resultArray = callStoredProcedure('CheckUsers', array($user));

        if (empty ($resultArray)) {
            echo json_encode(['error' => 'User does not exist']);
        } else {
            foreach ($resultArray as $row) {
                if (password_verify($pass, $row['pass'])) {
                    $_SESSION['username'] = $user;
                    $_SESSION['role'] = $row['role_id'];
                    $USERNAME = $user;
                    error_log($USERNAME);
                    echo json_encode(['redirect' => 'https://thor.cnt.sast.ca/~uyaghma1/CMPE2550_Projects/big_brain_codes/lab03/pages/index.php']);
                }
                else {
                    echo json_encode(['error' => "Incorrect password"]);
                }
            }
        }
    }
}

function Delete()
{
    if (isset ($_POST['role'])) {
        $role = $_POST['role'];
        $rolename = $_POST['rolename'];
        callStoredProcedure('DeleteData', array($role, NULL));
        $output = RetrieveRoles($_SESSION['role']);
        $status = "Deleted $rolename successfully";
    } else {
        $username = $_POST['user'];
        $userid = $_POST['id'];
        callStoredProcedure('DeleteData', array(NULL, $userid));
        $output = RetrieveData($_SESSION['role']);
        $status = "Deleted $username successfully";
    }

    echo json_encode(['status' => $status, 'output' => $output]);
}

function Update()
{
    if (isset ($_POST['role'])) {
        $username = $_POST['user'];
        $userid = $_POST['id'];
        $role = $_POST['role'];
        $resultArray = callStoredProcedure('UpdateRole', array($userid, $role));

        if (!empty ($resultArray) && isset ($resultArray[0]['error'])) {
            echo json_encode(['error' => $resultArray[0]['error']]);
        } else {
            $output = RetrieveData($_SESSION['role']);
            echo json_encode(['output' => $output, 'status' => "Updated $username's role successfully"]);
        }
    }
}

function AddUser()
{
    if (intval($_POST['role']) == 0) {
        echo json_encode(['roleerror' => 'Select a valid role']);
    } 
    else {
        if (isset ($_POST['username']) && isset ($_POST['password']) && isset ($_POST['role'])) {
            $user = strip_tags(trim($_POST['username']));
            $pass = strip_tags(trim($_POST['password']));
            $role = strip_tags(trim($_POST['role']));
            $hashedpass = password_hash($pass, PASSWORD_BCRYPT);

            if (strlen($user) < 8 || strlen($user) > 15) {
                echo json_encode(['usererror' => 'Username must be 8 to 15 characters long']);
            }
            else {
                $resultArray = callStoredProcedure('AddUser', array($user, $hashedpass, $role));
                if (!empty ($resultArray) && isset ($resultArray[0]['error_message'])) {
                    echo json_encode(['error' => $resultArray[0]['error_message']]);
                }
                else {
                    $output = RetrieveData($_SESSION['role']);
                    echo json_encode(['output' => $output, 'status' => "Successfully added $user"]);
                }
            }
        }
    }
}

function AddRole()
{
    if (isset ($_POST['desc']) && isset ($_POST['roleName'])) {
        $desc = strip_tags(trim($_POST['desc']));
        $roleName = strip_tags(trim($_POST['roleName']));

        $params = array($desc, $roleName, '');

        $resultArray = callStoredProcedure('AddRole', $params);

        if (!empty ($resultArray) && isset ($resultArray[0]['error'])) {
            echo json_encode(['error' => $resultArray[0]['error']]);
        } 
        else {
            $output = RetrieveRoles($_SESSION['role']);
            echo json_encode(['output' => $output, 'status' => "Successfully added $roleName"]);
        }
    }
}

function RetrieveRoles($role)
{
    $procedureName = 'GetRoles';
    $params = array();
    $resultArray = callStoredProcedure($procedureName, $params);

    $table = "<table class='table'>";
    $table .= "<thead><th>Action</th><th>ID</th><th>Name</th><th>Description</th></thead><tbody>";

    foreach ($resultArray as $row) {
        if ($row['role_id'] >= $role) {
            $table .= "<tr>";
            $table .= "<td class='action' id='" . $row['role_id'] . "'>" . "<a type='button' class='btn btn-primary rounded-pill px-3 delete' rid='" . $row['role_id'] . "'>Delete</a></td>";
            $table .= "<td id='" . $row['role_id'] . "' class='role-id'>" . $row['role_id'] . "</td>";
            $table .= "<td id='" . $row['role_id'] . "' class='role-name'>" . $row['name'] . "</td>";
            $table .= "<td id='" . $row['role_id'] . "' class='role-description'>" . $row['description'] . "</td>";
            $table .= "</tr>";
        }
    }

    $table .= "</tbody></table>";

    return $table;
}

function RetrieveData($role)
{
    global $mysql_connection;

    $resultArray = callStoredProcedure('GetUserInfo', array());

    if (empty ($resultArray)) {
        return "No data found.";
    }

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

    foreach ($resultArray as $row) {
        if ($row['role_id'] >= $role) {
            $table .= "<tr>";
            if ($role == 1) {
                $table .= "<td class='delete-cell'><a type='button' class='btn btn-primary rounded-pill px-3 delete' id='" . $row['user_id'] . "'>Delete</a></td>";
                $table .= "<td class='update-cell'><a type='button' class='btn btn-primary rounded-pill px-3 update' id='" . $row['user_id'] . "'>Update</a></td>";
            }
            $table .= "<td class='id-cell'>" . $row['user_id'] . "</td>"
                . "<td class='username-cell' id='" . $row['user_id'] . "'>" . $row['username'] . "</td>"
                . "<td class='pass-cell' id='" . $row['user_id'] . "'>" . $row['pass'] . "</td>"
                . "<td class='role-cell' id='" . $row['user_id'] . "'>";
            $table .= "<select name='roles' class='form-control select-cell' id='" . $row['user_id'] . "'>";
            $rolesResultArray = callStoredProcedure('GetRoles', array());
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

function FetchRoles($role)
{
    $rolesResultArray = callStoredProcedure('GetRoles', array());

    if (empty ($rolesResultArray)) {
        return 'Selection query failed';
    } 
    else {
        $addusers = "";
        foreach ($rolesResultArray as $row) {
            $addusers .= "<option value='default' selected hidden>Select a role</option>";
            if ($row['role_id'] >= $role) {
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
    $redirect = 'https://thor.cnt.sast.ca/~uyaghma1/CMPE2550_Projects/big_brain_codes/lab03/pages/login.php';

    echo json_encode(['redirect' => $redirect]);
}