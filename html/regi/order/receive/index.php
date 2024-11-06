<?php
session_start();

if (!isset($_POST["order_id"]) || $_POST["order_id"] === "") {
    $_SESSION["message"] = "指定した注文はありません。";
    $_SESSION["message_type"] = "warning";
    session_write_close();
    header("Location ../list/");
    exit();
}

$order_id = $_POST["order_id"];

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";
try {
    $order = new Order();
    $order = $order->get_from_order_id($order_id);
    $order->get_order_order()->receive();

    $_SESSION["message"] = "注文番号 {$order->get_order_order()->get_id()} の受け取りが完了しました。";
    $_SESSION["message_type"] = "success";
} catch (\Throwable $e) {
    $_SESSION["message"] = "エラーが発生しました。";
    $_SESSION["message_details"] = $e->getMessage();
    $_SESSION["message_type"] = "danger";
}
session_write_close();
header("Location: ../list/");
exit();