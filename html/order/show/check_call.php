<?php
if (isset($_COOKIE["order"]) && $_COOKIE["order"] !== "") {
    try {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";
        require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/decrypt_id.php";
        $order = new Order();
        $id = decrypt_id(htmlspecialchars($_COOKIE["order"]));
        $order->get_from_order_id($id);
        echo json_encode(["is_call" => $order->get_order_order()->get_is_call()]);
        exit();
    } catch (Exception $e) {
        http_response_code(404);
        exit();
    } catch (\Throwable $th) {
        http_response_code(500);
        exit();
    }
}