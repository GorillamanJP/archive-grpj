<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_alert_with_form.php";

session_start();

if (!isset($_SESSION["order"]["captcha"]["after"]["url"]) || $_SESSION["order"]["captcha"]["after"]["url"] === "") {
    $after_url = "/order/";
    $message_details = "本来表示するべきページへの宛先情報がなかったため、トップページに移動しました。";
} else {
    $after_url = $_SESSION["order"]["captcha"]["after"]["url"];
}

if (isset($_POST['captcha']) && htmlspecialchars($_POST['captcha']) == $_SESSION["order"]["captcha"]["code"]) {
    unset($_SESSION["order"]["captcha"]["code"]);
    $_SESSION["message"] = "認証に成功しました。";
    $_SESSION["message_type"] = "success";
    $_SESSION["order"]["captcha"]["success"] = true;
    $post_data = isset($_SESSION["order"]["captcha"]["post_data"]) ? $_SESSION["order"]["captcha"]["post_data"] : "";
    unset($_SESSION["order"]["captcha"]["after"]);
    unset($_SESSION["order"]["captcha"]["before"]);
    unset($_SESSION["order"]["captcha"]["post_data"]);
    session_write_close();
    if ($post_data) {
        redirect_with_error_with_form($after_url, "認証に成功しました。", isset($message_details) ? $message_details : "", "success", $post_data);
    } else {
        header("Location: {$after_url}");
        exit();
    }
} else {
    redirect_with_error("./", "認証に失敗しました。", "", "warning");
}
