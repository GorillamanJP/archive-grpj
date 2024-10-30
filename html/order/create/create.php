<?php
session_start();

if(!isset($_SESSION["order"]["captcha"]["success"]) || $_SESSION["order"]["captcha"]["success"] == false){
    $_SESSION["message"] = "CAPTCHA認証ができていません。";
    $_SESSION["message_type"] = "warning";
    session_write_close();
    header("Location: ./");
    exit();
}
$_SESSION["order"]["captcha"]["success"] = false;

header("Location: ../show/");