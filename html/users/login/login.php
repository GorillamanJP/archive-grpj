<?php
$user_name = htmlspecialchars($_POST["user_name"], encoding: "UTF-8");
$password = htmlspecialchars($_POST["password"], encoding: "UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/user.php";


session_start();

try {
    $user = new User();
    $user = $user->get_from_user_name($user_name);
    $user = $user->verify($password);
    $_SESSION["login"]["user_id"] = $user->get_id();
    if(isset($_SESSION["login"]["after"])){
        header("Location: " . $_SESSION["login"]["after"]);
    } else {
        header("Location: ./regi/");
    }
    exit();
} catch (Exception $e) {
    $_SESSION["error"]["message"] = "ユーザー名またはパスワードが違います。";
    header("Location: ./");
}