<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_alert_with_form.php";

session_start();

if (!isset($_POST["id"]) || $_POST["id"] === "") {
    redirect_with_error("../list/", "ユーザーIDが指定されていません。", "", "warning");
}

$id = htmlspecialchars($_POST["id"]);

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
    redirect_with_error_with_form("./", $message, "", "warning", $_POST);
}

$user_name = htmlspecialchars($_POST["user_name"]);
$password = htmlspecialchars($_POST["password"]);

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/users/user.php";
    $user = new User();
    $user = $user->get_from_id($id);
    $user = $user->update($user_name, $password);

    redirect_with_error("../list/", "ユーザー情報を更新しました。", "", "success");
} catch (Throwable $e) {
    redirect_with_error_with_form("./", "エラーが発生しました。", $e->getMessage(), "danger", $_POST);
}