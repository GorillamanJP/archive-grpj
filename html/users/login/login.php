<?php
$username = htmlspecialchars($_POST["username"], encoding: "UTF-8");
$password = htmlspecialchars($_POST["password"], encoding: "UTF-8");
login($username, $password);

function login($username, $password)
{
    require_once $_SERVER['DOCUMENT_ROOT'] . "/users/user.php";
    $user = new User();
    if (!is_null($user->get_from_username($username)->verify($password))) {
        header("Location: ./login_success.html");
    } else {
        header("Location: ./login_fail.html");
    }
}