<?
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
    case 'usermng':
        UserManagement($_POST['role_id']);
        break;
    case 'rolemng':
        RoleManagement($_POST['role_id']);
        break;
    case 'messages':
        Messages($_POST['role_id']);
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

        if (!($results = mySelectQuery($query))) {
            echo "Selection query failed";
        } else {
            while ($row = $results->fetch_assoc()) {
                if (password_verify($pass, $row['pass'])) {
                    $status = "Welcome back $user!";
                    Redirect('https://thor.cnt.sast.ca/~uyaghma1/CMPE2550_Projects/big_brain_codes/lab02/index.php');
                    if ($row['role_id'] == 1) {
                        $html = "<div class='form-group usermng-btn'>
                                    <a type='button' class='btn btn-primary rounded-pill' id='user-mng'>User Management</a>
                                </div>
                                <div class='form-group rolemng-btn'>
                                    <a type='button' class='btn btn-primary rounded-pill' id='role-mng'>Role Management</a>
                                </div>
                                <div class='form-group messages-btn'>
                                    <a type='button' class='btn btn-primary rounded-pill' id='messages'>Messages</a>
                                </div>";
                    } else if ($row['role_id'] == 2) {
                        $html = "<div class='form-group usermng-btn'>
                                    <a type='button' class='btn btn-primary rounded-pill' id='user-mng'>User Management</a>
                                </div>
                                <div class='form-group rolemng-btn'>
                                    <a type='button' class='btn btn-primary rounded-pill' id='role-mng'>Role Management</a>
                                </div>
                                <div class='form-group messages-btn'>
                                    <a type='button' class='btn btn-primary rounded-pill' id='messages'>Messages</a>
                                </div>";
                    } else if ($row['role_id'] == 3) {
                        $html = "<div class='form-group rolemng-btn'>
                                    <a type='button' class='btn btn-primary rounded-pill' id='role-mng'>Role Management</a>
                                </div>
                                <div class='form-group messages-btn'>
                                    <a type='button' class='btn btn-primary rounded-pill' id='messages'>Messages</a>
                                </div>";
                    } else if ($row['role_id'] == 4) {
                        $html = "<div class='form-group messages-btn'>
                                    <a type='button' class='btn btn-primary rounded-pill' id='messages'>Messages</a>
                                </div>";
                    }
                } else {
                    $error = "Incorrect password";
                }
            }
        }
    }

    echo json_encode(['status' => $status, 'error' => $error, 'username' => $user, 'buttons' => $html]);
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

function UserManagement($role)
{
    $table = "<table class='table'>
    <thead>";
    if ($role == 1) {
        $table .= "<th>Action</th>";
    }
    $table .= "<th>userID</th>
        <th>Username</th>
        <th>Hashed Password</th>
        <th>Change role</th>
    </thead>
    <tbody class='table-body'>";

    $query = "SELECT * FROM userinfo";
    if (!($results = mySelectQuery($query))) {
        echo "Selection query failed";
    } else {
        while ($row = $results->fetch_assoc()) {
            $table .= "<tr>";
            if ($role == 1) {
                $table .= "<td class='delete-cell'><a type='button' class='btn btn-primary rounded-pill px-3 delete' id='" . $row['user_id'] . "'>Delete</a></td>";
            }
            $table .= "<td class='id-cell'>" . $row['user_id'] . "</td>"
                . "<td class='username-cell' id='" . $row['user_id'] . "'>" . $row['username'] . "</td>"
                . "<td class='pass-cell' id='" . $row['user_id'] . "'>" . $row['pass'] . "</td>"
                . "<td class='role-cell' id='" . $row['user_id'] . "'>";
            $table .= "<select name='roles' class='form-control' id='roles'>";
            $rquery = 'SELECT DISTINCT * FROM roles';
            if (!($rresults = mySelectQuery($rquery))) {
                echo 'Selection query failed';
            } else {
                while ($rrow = $rresults->fetch_assoc()) {
                    if ($rrow['role_id'] > $role) {
                        if ($rrow['role_id'] == $role)
                            $table .= "<option value='" . $rrow['role_id'] . "' selected>" . $rrow['name'] . '</option>';
                        else
                            $table .= "<option value='" . $rrow['role_id'] . "'>" . $rrow['name'] . '</option>';
                    }
                }
                $table .= "</select>";
            }
            $table .= "</td>"
                . "</tr>";
        }
        $table .= "</tbody>
        </table>";
    }

    $addusers = "<h1 style='text-align: center; margin-bottom: 1.5rem'>Add Users</h1>
        <form class='needs-validation'>
            <div class='form-group'>
                <div class='input-group'>
                    <div class='input-group-addon'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-person-fill' viewBox='0 0 16 16'>
                            <path d='M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6' />
                        </svg>
                    </div>
                    <input type='text' name='username' id='username-add' class='form-control' placeholder='Username' aria-describedby='user-validity'>
                    <div class='user' style='display: none;'>
                        <small id='user-validity' class='text-muted'>Username must be 8 to 15 characters in length</small>
                    </div>
                </div>
            </div>
            <div class='form-group'>
                <div class='input-group'>
                    <div class='input-group-addon'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-key-fill' viewBox='0 0 16 16'>
                            <path d='M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2M2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2' />
                        </svg>
                    </div>
                    <input type='password' name='password' id='password-add' class='form-control' placeholder='Password'>
                    <div class='input-group-addon' id='show-pass-add'>
                        <a href=''><i class='fa fa-eye-slash' aria-hidden='true' style='margin-top: 11px; margin-right: 10px;'></i></a>
                    </div>
                </div>
                <div class='pass' style='display: none;'>
                    <small id='pass-title' class='muted'>Password must: </small><br>
                    <small id='pass-length' class='muted'>be at least 8 characters in length</small><br>
                    <small id='pass-upper' class='muted'>contain at least 1 uppercase letter</small><br>
                    <small id='pass-lower' class='muted'>contain at least 1 lowercase letter</small><br>
                    <small id='pass-special' class='muted'>contain at least 1 special character</small><br>
                    <small id='pass-number' class='muted'>contain at least 1 number</small>
                </div>
            </div>
            <div class='form-group'>
                <div class='input-group'>
                    <select name='roles' class='form-control' id='roles'>
                        <option selected hidden>Select a role</option>";
    $query = 'SELECT DISTINCT * FROM roles';
    if (!($results = mySelectQuery($query))) {
        echo 'Selection query failed';
    } else {
        while ($row = $results->fetch_assoc()) {
            if ($row['role_id'] != 1 && $row['role_id'] != 2) {
                $addusers .= "<option value='" . $row['role_id'] . "'>" . $row['name'] . '</option>';
            }
        }
        $addusers .= "</select>
                            </div>
                        </div>
                        <div class='form-group register-btn'>
                            <a type='submit' class='btn btn-primary rounded-pill' id='add-user'>Add User</a>
                        </div>
                    </form>";
    }
    $html = ['table' => $table, 'adduser' => $addusers];
    return $html;
}

function RoleManagement($role)
{
    $addroles = "<form class='needs-validation'>
        <div class='form-group'>
            <div class='input-group'>
                <div class='input-group-addon'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-person-fill-gear' viewBox='0 0 16 16'>
                        <path d='M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4m9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0'/>
                    </svg>              
                </div>
                <input type='text' name='roleName' id='roleName' class='form-control' placeholder='Role Name' aria-describedby='user-validity'>
                <div class='user' style='display: none;'>
                    <small id='user-validity' class='text-muted'>Invalid role name</small>
                </div>
            </div>
        </div>
        <div class='form-group'>
            <div class='input-group'>
                <div class='input-group-addon'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-info-circle-fill' viewBox='0 0 16 16'>
                        <path d='M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2'/>
                    </svg>              
                </div>
                <input type='text' name='roleDesc' id='roleDesc' class='form-control' placeholder='Role Description'>
                <div class='input-group-addon' id='show-pass-add'>
                    <a href=''><i class='fa fa-eye-slash' aria-hidden='true' style='margin-top: 11px; margin-right: 10px;'></i></a>
                </div>
            </div>
        </div>
        <div class='form-group register-btn'>
            <a type='submit' class='btn btn-primary rounded-pill' id='add-role'>Add Role</a>
        </div>
    </form>";

    $table = "<table class='table'>
    <thead>
        <th>userID</th>
        <th>Username</th>
        <th>Hashed Password</th>
        <th>Change role</th>
    </thead>
    <tbody class='table-body'>";

    $query = "SELECT * FROM userinfo";
    if (!($results = mySelectQuery($query))) {
        echo "Selection query failed";
    } else {
        while ($row = $results->fetch_assoc()) {
            $table .= "<tr>"
                . "<td class='id-cell'>" . $row['user_id'] . "</td>"
                . "<td class='username-cell' id='" . $row['user_id'] . "'>" . $row['username'] . "</td>"
                . "<td class='pass-cell' id='" . $row['user_id'] . "'>" . $row['pass'] . "</td>"
                . "<td class='role-cell' id='" . $row['user_id'] . "'>";
            $table .= "<select name='roles' class='form-control' id='roles'>";
            $rquery = 'SELECT DISTINCT * FROM roles';
            if (!($rresults = mySelectQuery($rquery))) {
                echo 'Selection query failed';
            } else {
                while ($rrow = $rresults->fetch_assoc()) {
                    if ($rrow['role_id'] >= $role) {
                        if ($rrow['role_id'] == $role)
                            $table .= "<option value='" . $rrow['role_id'] . "' selected>" . $rrow['name'] . '</option>';
                        else
                            $table .= "<option value='" . $rrow['role_id'] . "'>" . $rrow['name'] . '</option>';
                    }
                }
                $table .= "</select>";
            }
            $table .= "</td>"
                . "</tr>";
        }
        $table .= "</tbody>
        </table>";
    }

    $html = ['table' => $table, 'addrole' => $addroles];
    return $html;
}

function Messages($role)
{
    $html = ['table' => "Message functionality", 'addrole' => "Not yet implemented"];
    return $html;
}