<?
    session_start();
    require_once("../utility/dbUtil.php");
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
    <link rel="stylesheet" href="../utility/style.css">
    <title>LAB02</title>
</head>

<body>
    <div class="container-fluid page-container">
        <div class="container-fluid parent" style="width: 40%;">
            <div class="container-sm main login rounded-lg" style="width: 400px; padding-bottom: 25px;">
                <h1 style="text-align: center; margin-bottom: 1.5rem">Login</h1>
                <form class="needs-validation">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                </svg>
                            </div>
                            <input type="text" name="username" id="username-login" class="form-control" placeholder="Username" aria-describedby="user-validity" autocomplete="off">
                        </div>
                        <div class="user-valid" style="display: none;">
                            <small id="user-dne" class="muted">User does not exist</small><br>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 16 16">
                                    <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2M2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                                </svg>
                            </div>
                            <input type="password" name="password" id="password-login" class="form-control" placeholder="Password" autocomplete="off">
                            <div class="input-group-addon" id="show-pass-login">
                                <a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="margin-top: 11px; margin-right: 10px;"></i></a>
                            </div>
                        </div>
                        <div class="pass" style="display: none;">
                            <small id="pass-incorrect" class="muted">Incorrect Password</small><br>
                        </div>
                    </div>
                    <div class="form-group login-btn">
                        <a type='submit' class='btn btn-primary rounded-pill login' id="login">Login</a>
                    </div>
                    <div class="form-group register-btn1">
                        <p>Don't have an account? &emsp; &emsp;<a href="#" class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" id="register-btn">Register Here</a></p>
                    </div>
                </form>
            </div>
            <div class="container-sm main register rounded-lg" style="width: 400px;">
                <h1 style="text-align: center; margin-bottom: 1.5rem">Register</h1>
                <form class="needs-validation">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                </svg>
                            </div>
                            <input type="text" name="username" id="username-register" class="form-control" placeholder="Username" aria-describedby="user-validity" autocomplete="off">
                            <div class="user" style="display: none;">
                                <small id="user-validity" class="text-muted">Username must be 8 to 15 characters in length</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 16 16">
                                    <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2M2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                                </svg>
                            </div>
                            <input type="password" name="password" id="password-register" class="form-control" placeholder="Password" autocomplete="off">
                            <div class="input-group-addon" id="show-pass-register">
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
                    <div class="form-group register-btn">
                        <a type='submit' class='btn btn-primary rounded-pill' id="register">Register</a>
                    </div>
                    <div class="form-group login-btn">
                        <p>Already have an account? &emsp; &emsp;<a href="#" class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" id="login-btn">Login Here</a></p>
                    </div>
                </form>
            </div>
            <div class="container-sm status-out">
                
            </div>
        </div>
        <div class="container-fluid aside">
            <h1 class="display-1" id="welcome">Welcome</h1>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="../utility/script.js"></script>
</body>

</html>