<?php
$id = htmlspecialchars($_GET["id"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/users/user.php";

$user = new User();
$user = $user->get_from_id($id);
$user->delete();

echo "OK";