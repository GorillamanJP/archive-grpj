<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";

session_start();
if (!isset($_POST["id"]) || $_POST["id"] === "") {
    redirect_with_error("../list/", "ユーザーIDが指定されていません。", "", "warning");
}

$id = htmlspecialchars($_POST["id"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/users/user.php";

try {
    $user = new User();
    $user = $user->get_from_id($id);
    $user->delete();

    $_SESSION["message"] = "ユーザーは正常に削除されました。";
    $_SESSION["message_type"] = "success";
    redirect_with_error("../list/", "ユーザーは正常に削除されました。", "", "success");
} catch (Throwable $e) {
    redirect_with_error("../list/", "エラーが発生しました。", $e->getMessage(), "danger");
}