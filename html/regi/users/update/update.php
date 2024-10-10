<?php
$id = htmlspecialchars($_POST["id"]);
$user_name = htmlspecialchars($_POST["user_name"]);
$password = htmlspecialchars($_POST["password"]);

try {
    $user = new User();
    $user = $user->get_from_id($id);
    $user = $user->update($user_name, $password);
    echo "OK";
} catch (Exception $e) {
    echo $e->getMessage();
    echo "NG";
}