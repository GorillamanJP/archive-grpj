<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/user.php";
session_start();
if (isset($_SESSION["login"]["user_id"])) {
    try {
        $user = new User();
        $user = $user->get_from_id($_SESSION["login"]["user_id"]);
    } catch (\Throwable $e) {
        $_SESSION["message"] = "セッションエラー: ログインしなおしてください。";
        $_SESSION["message_type"] = "danger";
        $_SESSION["login"]["after"]["url"] = $_SERVER['REQUEST_URI'];
        if (isset($_POST)) {
            $_SESSION["login"]["after"]["post_data"] = $_POST;
        }
        header("Location: /regi/users/login/");
        exit();
    }
} else {
    $_SESSION["message"] = "このページを表示するには、ログインが必要です。";
    $_SESSION["message_type"] = "warning";
    $_SESSION["login"]["after"]["url"] = $_SERVER['REQUEST_URI'];
    if (isset($_POST)) {
        $_SESSION["login"]["after"]["post_data"] = $_POST;
    }
    header("Location: /regi/users/login/");
    exit();
}
session_write_close();