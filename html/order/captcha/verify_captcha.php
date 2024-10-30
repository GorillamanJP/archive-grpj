<?php
session_start();

if (!isset($_SESSION["order"]["captcha"]["after"]["url"]) || $_SESSION["order"]["captcha"]["after"]["url"] === "") {
    $after_url = "/order/";
    $_SESSION["message_details"] = "本来表示するべきページへの宛先情報がなかったため、トップページに移動しました。";
} else {
    $after_url = $_SESSION["order"]["captcha"]["after"]["url"];
}

$_SESSION["order"]["captcha"]["success"] = false;

if ($_POST['captcha'] == $_SESSION["order"]["captcha"]["code"]) {
    unset($_SESSION["order"]["captcha"]["code"]);
    $_SESSION["message"] = "認証に成功しました。";
    $_SESSION["message_type"] = "success";
    $_SESSION["order"]["captcha"]["success"] = true;
    unset($_SESSION["order"]["captcha"]["after"]["url"]);
    unset($_SESSION["order"]["captcha"]["before"]["url"]);
    session_write_close();
    header("Location: {$after_url}");
    exit();
} else {
    $_SESSION["message"] = "認証に失敗しました。";
    $_SESSION["message_type"] = "danger";
    session_write_close();
    header("Location: ./");
    exit();
}
