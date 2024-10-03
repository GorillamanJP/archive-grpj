<?php
$user_name = htmlspecialchars($_POST["user_name"], encoding: "UTF-8");
$password = htmlspecialchars($_POST["password"], encoding: "UTF-8");
login($user_name, $password);

function login($user_name, $password):bool
{
    require_once $_SERVER['DOCUMENT_ROOT'] . "/users/user.php";
    $user = new User();
    if (!is_null($user->get_from_user_name($user_name)->verify($password))) {
        return true;
    } else {
        return false;
    }
}