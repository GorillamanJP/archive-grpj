<?php
session_start();

if (!isset($_COOKIE["order"])) {
    http_response_code(400);
    exit();
}

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/order_notifies/order_notify.php";

    $order_notify = new Order_Notify();

    echo json_encode(["key" => $order_notify->get_public_key()]);
} catch (Throwable $t) {
    http_response_code(500);
    echo json_encode(["Error" => $t->getMessage()]);
}