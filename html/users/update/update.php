<?php
$id = htmlspecialchars($_POST["id"]);
$username = htmlspecialchars($_POST["username"]);
$password = htmlspecialchars($_POST["password"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/users/user.php";
$user = new User();
$user = $user->get_from_id($id);
$user = $user->update($username, $password);
if($user){
    echo "OK";
} else {
    echo "Fail";
}