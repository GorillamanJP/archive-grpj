<?php
$id = htmlspecialchars($_POST["id"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/users/user.php";

$user = new User();
$user = $user->get_from_id($id);
$user->delete();

echo "OK";