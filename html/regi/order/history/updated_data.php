<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check_bool.php";
if (!login_check()) {
    http_response_code(403);
    exit();
}

session_start();

if (!isset($_POST["page_offset"]) || !isset($_POST["page_limit"])) {
    http_response_code(400);
    exit();
}

$offset = htmlspecialchars($_POST["page_offset"]);
$limit = htmlspecialchars($_POST["page_limit"]);

$last_update = date("Y/m/d H:i:s");
$_SESSION["regi"]["order"]["history"]["last_update"] = $last_update;

$data = "";
try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";
    $order_obj = new Order();
    $orders = $order_obj->gets_range($offset, $limit);
    $page = "./no_list.php";
    if (!is_null($orders)) {
        $page = "./list.php";
    }
    ob_start();
    require $page;
    $data = ob_get_contents();
    ob_end_clean();

    $all_order = $order_obj->get_all_all();
    $order_count = 0;
    if(!is_null($all_order)){
        $order_count = ceil(count($all_order) / $limit);
    }
    $page_end = $order_count;

    echo json_encode([
        "last-update" => $last_update,
        "table" => $data,
        "page_end" => $page_end,
    ]);
    exit();

} catch (Exception $e) {
    http_response_code(500);
    exit();
}