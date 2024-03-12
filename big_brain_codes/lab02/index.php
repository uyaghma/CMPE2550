<?
require_once("dbUtil.php");
mySQLConnection();
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
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="style.css">
    <title>LAB02</title>
</head>

<body>
    <div class="container-sm parent">
        <div class="container-sm">

        </div>
        <div class="container-sm main rounded-lg" style="width: 400px;">
            <h1 style="text-align: center; margin-bottom: 1.5rem">Login</h1>
            <form class="needs-validation">
                <div class="form-group">
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username"
                        aria-describedby="user-validity">
                    <div class="user" style="display: none;">
                        <small id="user-validity" class="text-muted">Username must be 8 to 15 characters in
                            length</small>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Password">
                        <div class="input-group-addon" id="show-pass">
                            <a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="margin-top: 11px; margin-right: 10px;"></i></a>
                        </div>
                    </div>
                    <div class="pass" style="display: none;">
                        <small id="pass-title" class="muted">Password must: </small><br>
                        <small id="pass-length" class="muted">be at least 8 characters in length</small><br>
                        <small id="pass-upper" class="muted">contain at least 1 uppercase letter</small><br>
                        <small id="pass-lower" class="muted">contain at least 1 lowercase letter</small><br>
                        <small id="pass-special" class="muted">contain at least 1 special character</small><br>
                        <small id="pass-number" class="muted">contain at least 1 number</small>
                    </div>
                </div>
                <div class="form-group login">
                    <a type='submit' class='btn btn-primary rounded-pill login' id="login">Login</a>
                </div>
                <div class="form-group register">
                    <p>Don't have an account? <a href="#"
                        class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Register Here</a></p>
                </div>
            </form>
        </div>
        <div class="container-sm">

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
</body>

</html>