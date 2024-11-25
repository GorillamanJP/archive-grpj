<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";

session_start();

if (!isset($_SESSION["regi"]["order"]["id"]) || $_SESSION["regi"]["order"]["id"] === "") {
    redirect_with_error("../list/", "指定した注文はありません。", "", "danger");
}

$order_id = $_SESSION["regi"]["order"]["id"];
unset($_SESSION["regi"]["order"]["id"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";
try {
    $order = new Order();
    $order = $order->get_from_order_id($order_id);
    $details = $order->get_order_details();

    $order->get_order_order()->receive();

    redirect_with_error("../list/", "注文番号 {$order->get_order_order()->get_id()} の受け取りが完了しました。", "", "success");
} catch (Throwable $e) {
    redirect_with_error("../list/", "エラーが発生しました。", $e->getMessage(), "danger");
}