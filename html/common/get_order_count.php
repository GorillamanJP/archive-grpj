<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check_bool.php";
if (!login_check()) {
    http_response_code(403);
    exit();
}

session_start();

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";
    $order = new Order();
    $orders = $order->get_all();
    $count = 0;
    if (!is_null($orders)) {
        $count = count($orders);
    }
    echo json_encode(["order_count" => $count]);
    session_write_close();
    exit();
} catch (Throwable $th) {
    http_response_code(500);
    // echo json_encode(["Error" => $th->getMessage()]);
    session_write_close();
    exit();
}