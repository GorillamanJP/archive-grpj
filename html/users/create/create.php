<?php
$user_name = $_POST["user_name"];
$password = $_POST["password"];
create($user_name, $password);
function create($user_name, $password)
{
    require_once $_SERVER['DOCUMENT_ROOT'] . "/users/user.php";
    $user = new User();
    $user = $user->create($user_name, $password);
    if (!is_null($user)) {
        header("Location: ./success.html");
    } else {
        header("Location: ./fail.html");
    }
}