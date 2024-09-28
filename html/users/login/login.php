<?php
$username = $_POST["username"];
$password = $_POST["password"];
login($username, $password);

function login($username, $password)
{
    require_once $_SERVER['DOCUMENT_ROOT'] . "/users/user.php";
    $user = new User();
    if($user->get_from_username($username)->verify($password)){
        header("Location: ./login_success.html");
    } else {
        header("Location: ./login_fail.html");
    }
}