<?
session_start();
require_once("../utility/auth.php");
CheckRole(3);

require_once("../utility/dbUtil.php");
mySQLConnection();

require_once("../utility/ws.php");

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="Description" content="Enter your description here" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../utility/style.css">
    <title>Role Management</title>
</head>

<body>
    <div class="container-fluid page-container">
        <div class="container-fluid parent" style="width: 40%;">
            <div class="container-sm main add-user rounded-lg" style="width: 400px;">
            <h1 style='text-align: center; margin-bottom: 1.5rem'>Add Role</h1>
                <form class='needs-validation'>
                    <div class='form-group'>
                        <div class='input-group'>
                            <div class='input-group-addon'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-person-fill-gear' viewBox='0 0 16 16'>
                                    <path d='M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4m9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0' />
                                </svg>
                            </div>
                            <input type='text' name='roleName' id='roleName' class='form-control' placeholder='Role Name' aria-describedby='user-validity' autocomplete="off">
                        </div>
                        <div class="status" style="display: none;">
                            <small id="status"></small><br>
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class='input-group'>
                            <div class='input-group-addon'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-info-circle-fill' viewBox='0 0 16 16'>
                                    <path d='M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2' />
                                </svg>
                            </div>
                            <input type='text' name='roleDesc' id='roleDesc' class='form-control' placeholder='Role Description' autocomplete="off">
                        </div>
                    </div>
                    <div class='form-group addrole-btn'>
                        <a type='submit' class='btn btn-primary rounded-pill' id='add-role'>Add Role</a>
                    </div>
                    <div class='form-group logout-btn'>
                        <a type='button' class='btn btn-primary rounded-pill' id='goto-index'>Back to Index</a>
                        <a type='button' class='btn btn-primary rounded-pill' id='logout'>Logout</a>
                    </div>
                    <div class="container-sm status-out">
                
                    </div>
                </form>
            </div>
        </div>
        <div class="container-fluid aside">
            <h1 class="display-1">Role Management</h1>
            <div class="container-fluid table-container">
                <?
                    echo RetrieveRoles($role);
                ?>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="../utility/script.js"></script>
</body>

</html>