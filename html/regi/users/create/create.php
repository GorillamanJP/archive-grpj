<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";

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

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/users/user.php";
try {
    $user = new User();
    $user = $user->create($user_name, $password);

    redirect_with_error("../list/", "ユーザーが正常に登録されました。", "", "success");
} catch (Throwable $e) {
    redirect_with_error("./", "エラーが発生しました。", $e->getMessage(), "danger");
}