<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check_bool.php";
if (!login_check()) {
    http_response_code(403);
    exit();
}

session_start();

$last_update = date("Y/m/d H:i:s");
$_SESSION["regi"]["order"]["list"]["last_update"] = $last_update;

$data = "";
try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";
    $order_obj = new Order();
    $orders = $order_obj->get_all();
    $page = "./no_list.php";
    if (!is_null($orders)) {
        $page = "./list.php";
    }
    ob_start();
    require $page;
    $data = ob_get_contents();
    ob_end_clean();

    echo json_encode([
        "last-update" => $last_update,
        "table" => $data,
    ]);
    exit();

} catch (Exception $e) {
    http_response_code(500);
    exit();
}