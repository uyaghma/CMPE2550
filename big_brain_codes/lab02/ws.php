<?
require_once("dbUtil.php");
mySQLConnection();

switch ($_REQUEST['action']) {
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
}

function Register()
{

}

function Login()
{

}

function Delete()
{

}

function Update()
{
    
}