<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";

try {
    $orders = new Order()->get_all();

    $wait = [];
    $call = [];

    if ($orders) {
        foreach ($orders as $order) {
            if ($order->get_order_order()->get_is_call()) {
                $call[] = $order->get_order_order()->get_id();
            } else {
                $wait[] = $order->get_order_order()->get_id();
            }
        }
    }

    echo json_encode(["wait" => $wait, "call" => $call]);
    exit();
} catch (Throwable $t) {
    echo json_encode(["Error" => $t->getMessage()]);
    exit();
}