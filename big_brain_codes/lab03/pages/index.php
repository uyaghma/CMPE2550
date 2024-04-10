<?
    session_start();
    require_once("../utility/dbUtil.php");
    mySQLConnection();

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
    <title>Index</title>
</head>

<body>
        <div class="container-fluid page-container">
        <div class="container-fluid parent" style="width: 40%;">
            <div class="container-sm main index rounded-lg" style="width: 400px;">
                <? 
                    if ($role <= 2) {
                        $html = "<div class='form-group usermng-btn'>
                                    <a href='usermng.php' type='button' class='btn btn-primary rounded-pill' id='user-mng'>User Management</a>
                                </div>";
                    }
                    if ($role <= 3) {
                        $html .= "<div class='form-group rolemng-btn'>
                                    <a href='rolemng.php' type='button' class='btn btn-primary rounded-pill' id='role-mng'>Role Management</a>
                                  </div>";
                    }
                    $html .= "<div class='form-group messages-btn'>
                                <a href='messages.php' type='button' class='btn btn-primary rounded-pill' id='messages'>Messages</a>
                              </div>";
                    echo $html;
                ?>
                <div class='form-group logout-btn'>
                    <a type='button' class='btn btn-primary rounded-pill' id='logout'>Logout</a>
                </div>
            </div>
        </div>
        <div class="container-fluid aside">
            <h1 class="display-1">Index</h1>
            <div class="output container-sm">
                <? if (isset($_SESSION['username'])) { echo "<h1 class='header'>Welcome, " . $_SESSION['username'] . "</h1>"; }?>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="../utility/script.js"></script>
</body>

</html>