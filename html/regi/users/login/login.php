<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_alert_with_form.php";

session_start();

$ok = true;
$message = "";

if (!isset($_POST["user_name"]) || $_POST["user_name"] === "") {
    $message .= "「ユーザー名」";
    $ok = false;
}
if (!isset($_POST["password"]) || $_POST["password"] === "") {
    $message .= "「パスワード」";
    $ok = false;
}

if (!$ok) {
    $message .= "の項目が空になっています。";
    redirect_with_error("./", $message, "", "warning");
}

$user_name = htmlspecialchars($_POST["user_name"]);
$password = htmlspecialchars($_POST["password"]);

$after_url = isset($_SESSION["login"]["after"]["url"]) ? $_SESSION["login"]["after"]["url"] : "/regi/";
$after_post = isset($_SESSION["login"]["after"]["post_data"]) ? $_SESSION["login"]["after"]["post_data"] : [];

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/users/user.php";
    $user = new User();
    $user = $user->get_from_user_name($user_name);
    $user = $user->verify($password);
    session_regenerate_id(true);
    $_SESSION["login"]["user_id"] = $user->get_id();
    $_SESSION["login"]["last_activity"] = time();
    $_SESSION["login"]["token"] = hash("SHA3-512", $user->get_user_name() . $user->get_password_hash());
    if ($user->get_id() == 1) {
        if (isset($after_post)) {
            redirect_with_error_with_form($after_url, "ログインしました。", "このユーザーは初期ユーザーです。新たに使用する人のユーザーを作成し、このユーザーは早急に削除してください。", "danger", $after_post);
        } else {
            redirect_with_error($after_url, "ログインしました。", "このユーザーは初期ユーザーです。新たに使用する人のユーザーを作成し、このユーザーは早急に削除してください。", "danger");
        }
    } else {
        if (isset($after_post)) {
            redirect_with_error_with_form($after_url, "ログインしました。", "", "success", $after_post);
        } else {
            redirect_with_error($after_url, "ログインしました。", "", "success");
        }
    }
} catch (Throwable $e) {
    redirect_with_error("./", "エラーが発生しました。", $e->getMessage(), "danger");
}