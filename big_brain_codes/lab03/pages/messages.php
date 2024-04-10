<?
session_start();
require_once("../utility/auth.php");
CheckRole(4);

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
    <title>Messages</title>
</head>

<body>
    <div class="container-fluid page-container">
        <div class="container-fluid parent" style="width: 40%;">
            <div class="container-sm main add-user rounded-lg" style="width: 400px;">
                <h1 style='text-align: center; margin-bottom: 1.5rem'>Add Message</h1>
                <form class='needs-validation'>
                    <div class='form-group'>
                        <div class='input-group'>
                            <div class='input-group-addon'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                    <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5z" />
                                </svg>
                            </div>
                            <input type='text' name='filter' id='filter-add' class='form-control' placeholder='Filter' aria-describedby='user-validity' autocomplete="off">
                        </div>
                        <div class='form-group filter-btn' style='margin-top: 10px;'>
                            <a type='submit' class='btn btn-primary rounded-pill' id='add-filter'>Add Filter</a>
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class='input-group'>
                            <div class='input-group-addon'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-chat-left-dots-fill" viewBox="0 0 16 16">
                                    <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793zm5 4a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                                </svg>
                            </div>
                            <input type='text' name='message' id='message-add' class='form-control' placeholder='Message' autocomplete="off">
                        </div>
                        <div class="mess-status" style="display: none;">
                            <small id="mess-status"></small><br>
                        </div>
                        <div class='form-group message-btn' style='margin-top: 10px;'>
                            <a type='submit' class='btn btn-primary rounded-pill' id='add-message'>Add Message</a>
                        </div>
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
            <h1 class="display-1">Messages</h1>
            <div class="container-fluid table-container">

            </div>
            <div id="currentUser" style="display: none;"><? echo $_SESSION['username']; ?></div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="../utility/script.js"></script>
    <script src="../script.js"></script>
</body>

</html>