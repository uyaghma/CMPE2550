<?
session_start();
require_once("dbUtil.php");
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
}

function Register()
{
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $user = strip_tags(trim($_POST['username']));
        $pass = strip_tags(trim($_POST['password']));
        $hashedpass = password_hash($pass, PASSWORD_BCRYPT);

        $query = "INSERT INTO userinfo (username, pass, role_id) VALUES ('$user', '$hashedpass', 4)";
        myNonSelectQuery($query);

        error_log("Inside Register from postman");
        echo json_encode(['status' => "Successfully registered"]);
    }
}

function Login()
{
    error_log("Inside login from postman");
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $user = strip_tags(trim($_POST['username']));
        $pass = strip_tags(trim($_POST['password']));

        $query = "SELECT * FROM userinfo where username='$user'";
        error_log($query);

        if (!($results = mySelectQuery($query))) {
            error_log("Selection query failed");
        } else {
            while ($row = $results->fetch_assoc()) {
                if (password_verify($pass, $row['pass'])) {
                    $_SESSION['username'] = $user;
                    $_SESSION['role'] = $row['role_id'];
                } else {
                    $error = "Incorrect password";
                }
            }
        }
    }

    echo json_encode(['error' => $error, 'redirect' => "https://thor.cnt.sast.ca/~uyaghma1/CMPE2550_Projects/big_brain_codes/lab02/pages/index.php"]);
}

function Delete()
{

}

function Update()
{
    
}

function AddUser()
{
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $user = strip_tags(trim($_POST['username']));
        $pass = strip_tags(trim($_POST['password']));
        $role = strip_tags(trim($_POST['role']));
        $hashedpass = password_hash($pass, PASSWORD_BCRYPT);

        $query = "INSERT INTO userinfo (username, pass, role_id) VALUES ('$user', '$hashedpass', $role)";
        error_log($query);
        myNonSelectQuery($query);

        echo json_encode(['status' => "Successfully added"]);
    }
}

function Redirect($url)
{
    echo json_encode(['redirect' => $url]);
    exit;
}