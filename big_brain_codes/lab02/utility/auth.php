<?
session_start();

if (!isset($_SESSION['username']))
{
    echo "<script>window.location.replace('https://thor.cnt.sast.ca/~uyaghma1/CMPE2550_Projects/big_brain_codes/lab02/pages/login.php');</script>";
    exit();
}

function CheckRole($maxrole)
{
    $current_role = $_SESSION['role'];
    if ($current_role > $maxrole)
    {
        echo "<script>window.location.replace('https://thor.cnt.sast.ca/~uyaghma1/CMPE2550_Projects/big_brain_codes/lab02/pages/index.php');</script>";
        exit();
    }
}

