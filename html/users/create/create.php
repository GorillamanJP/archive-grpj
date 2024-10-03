<?php
$user_name = $_POST["user_name"];
$password = $_POST["password"];
if (create($user_name, $password)) {
}
function create($user_name, $password): bool
{
    require_once $_SERVER['DOCUMENT_ROOT'] . "/users/user.php";
    $user = new User();
    $user = $user->create($user_name, $password);
    if (!is_null($user)) {
        return true;
    } else {
        return false;
    }
}