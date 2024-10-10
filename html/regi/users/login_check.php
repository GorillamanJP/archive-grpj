<?php
function login_check()
{
    require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/user.php";
    session_start();
    if (isset($_SESSION["login"]["user_id"])) {
        try {
            $user = new User();
            $user = $user->get_from_id($_SESSION["login"]["user_id"]);
        } catch (Exception $e) {
            $_SESSION["error"]["message"] = "セッションエラー: ログインしなおしてください。";
            $_SESSION["login"]["after"] = $_SERVER['REQUEST_URI'];
            header("Location: /users/login/");
            exit();
        }
    } else {
        $_SESSION["error"]["message"] = "このページを表示するには、ログインが必要です。";
        $_SESSION["login"]["after"] = $_SERVER['REQUEST_URI'];
        header("Location: /users/login/");
        exit();
    }
    session_write_close();
}
login_check();