<?php
session_start();

if (!isset($_COOKIE["order"])) {
    http_response_code(400);
    exit();
}

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/decrypt_id.php";
    $order_id = decrypt_id($_COOKIE["order"]);

    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/order_notifies/order_notify.php";
    $order_notify = new Order_Notify();
    $order_notify = $order_notify->get_from_order_id($order_id);
    $order_notify->delete();

    http_response_code(200);
    exit();
} catch (Throwable $th) {
    http_response_code(500);
    echo json_encode(["Error" => $th->getMessage()]);
}