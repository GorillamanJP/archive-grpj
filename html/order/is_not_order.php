<?php
session_start();
if(!isset($_COOKIE["order"]) || $_COOKIE["order"] === ""){
    $_SESSION["message"] = "注文番号がありません。";
    $_SESSION["message_details"] = "あなたは注文をしていないようです。";
    $_SESSION["message_type"] = "warning";
    session_write_close();
    header("Location: /order/");
    exit();
}
session_write_close();