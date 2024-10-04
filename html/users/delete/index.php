<?php
$id = htmlspecialchars($_POST["id"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/users/user.php";
try {
    $user = new User();
    $user = $user->get_from_id($id);
    $user->delete();
    echo "OK";
}catch (Exception $e){
    echo $e->getMessage();
    echo "NG";
}