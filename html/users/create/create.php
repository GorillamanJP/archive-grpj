<?php
$username = $_POST["username"];
$password = $_POST["password"];
create($username, $password);
function create($username, $password)
{
    require_once $_SERVER['DOCUMENT_ROOT'] . "/users/user.php";
    $user = new User();
    $user = $user->create($username, $password);
    if (!is_null($user)) {
        header("Location: ./success.html");
    } else {
        header("Location: ./fail.html");
    }
}