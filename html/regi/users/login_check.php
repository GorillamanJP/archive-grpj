<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/users/user.php";

// セッションが切れるまでの時間(秒)
$inactive_time = 3600;

// ログイン失敗の処理
function login_fail(string $message, string $message_type, string $after_url, $post_data = null)
{
    $_SESSION["message"] = $message;
    $_SESSION["message_type"] = $message_type;
    $_SESSION["login"]["after"]["url"] = $after_url;
    if (isset($post_data)) {
        $_SESSION["login"]["after"]["post_data"] = $post_data;
    }
    session_write_close();
    header("Location: /regi/users/login/");
    exit();
}

// 本体
session_start();
if (isset($_SESSION["login"]["user_id"])) {
    if (!isset($_SESSION["login"]["last_activity"]) || (time() - $_SESSION["login"]["last_activity"]) > $inactive_time) {
        login_fail("1時間操作がなかったため、自動的にログアウトしました。", "info", $_SERVER['REQUEST_URI'], $_POST);
    } else {
        try {
            $user = new User();
            $user = $user->get_from_id($_SESSION["login"]["user_id"]);
            if (!isset($_SESSION["login"]["token"]) || $_SESSION["login"]["token"] !== hash("SHA3-512", $user->get_user_name() . $user->get_password_hash())) {
                $_SESSION["message_details"] = "ユーザー名またはパスワードが変更された可能性があります。";
                login_fail("再ログインが必要です。", "warning", $_SERVER['REQUEST_URI'], $_POST);
            }
            session_regenerate_id(true);
            $_SESSION["login"]["last_activity"] = time();
        } catch (\Throwable $e) {
            $_SESSION["message_details"] = "ログイン中のユーザーを削除した可能性があります。";
            login_fail("ログイン中のユーザーが見つかりませんでした。", "danger", $_SERVER['REQUEST_URI'], $_POST);
        }
    }
} else {
    login_fail("このページを表示するには、ログインが必要です。", "info", $_SERVER['REQUEST_URI'], $_POST);
}
session_write_close();