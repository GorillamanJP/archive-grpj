<?php
$user_name = htmlspecialchars($_POST["user_name"], encoding: "UTF-8");
$password = htmlspecialchars($_POST["password"], encoding: "UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . "/users/user.php";
try {
    $user = new User();
    $user = $user->get_from_user_name($user_name);
    $user = $user->verify($password);
    header("Location: ./login_success.php");
} catch (Exception $e) {
    session_start();
    $_SESSION["error"]["message"] = "ユーザー名またはパスワードが違います。";
    header("Location: ./");
}