<?php
$user_name = htmlspecialchars($_POST["user_name"]);
$password = htmlspecialchars($_POST["password"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/user.php";
try {
    $user = new User();
    $user = $user->create($user_name, $password);
    echo "OK";
} catch (Exception $e) {
    echo $e->getMessage();
    echo "NG";
}