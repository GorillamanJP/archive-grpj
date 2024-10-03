<?php
$id = htmlspecialchars($_POST["id"]);
$user_name = htmlspecialchars($_POST["user_name"]);
$password = htmlspecialchars($_POST["password"]);

function update($id, $user_name, $password): bool
{
    require_once $_SERVER['DOCUMENT_ROOT'] . "/users/user.php";
    $user = new User();
    $user = $user->get_from_id($id);
    $user = $user->update($user_name, $password);
    if (!is_null($user)) {
        return true;
    } else {
        return false;
    }
}