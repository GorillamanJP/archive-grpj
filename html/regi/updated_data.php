<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check_bool.php";
if (!login_check()) {
    http_response_code(403);
    exit();
}

session_start();

$dt = new DateTime();
$now = $dt->format("Y-m-d H:i:s.u");

$last_update = $now;
$_SESSION["list"]["last_update"] = $last_update;

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/products/product.php";
    $product_obj = new Product();
    $products = $product_obj->get_all();
    $page = $_SERVER['DOCUMENT_ROOT']."/common/index/no_list.php";
    if (!is_null($products)) {
        $page = "./list.php";
    }
    ob_start();
    require $page;
    $table = ob_get_contents();
    ob_end_clean();

    echo json_encode([
        "table" => $table,
        "last-update" => date_create($last_update)->format("Y/m/d H:i:s"),
    ]);
    exit();
} catch (\Throwable $th) {
    http_response_code(500);
    // echo json_encode(["Error" => $th->getMessage()]);
    exit();
}