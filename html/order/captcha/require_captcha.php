<?php
session_start();
if (!isset($_SESSION["order"]["captcha"]["success"]) || $_SESSION["order"]["captcha"]["success"] === false) {
    if (!empty($_POST)) {
        $_SESSION["order"]["captcha"]["after"]["post_data"] = $_POST;
    }
    $_SESSION["order"]["captcha"]["before"]["url"] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/order/";
    $_SESSION["order"]["captcha"]["after"]["url"] = $_SERVER['REQUEST_URI'];
    session_write_close();
    header("Location: /order/captcha/");
    exit();
}
$_SESSION["order"]["captcha"]["success"] = false;
session_write_close();
