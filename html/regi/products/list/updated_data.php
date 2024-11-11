<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check_bool.php";
if (!login_check()) {
    http_response_code(403);
    exit();
}

session_start();

$last_update = date("Y/m/d H:i:s");
$_SESSION["regi"]["product"]["list"]["last_update"] = $last_update;

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/products/product.php";
    $product_obj = new Product();
    $products = $product_obj->get_all();
    $page = "./no_list.php";
    if (!is_null($products)) {
        $page = "./list.php";
    }
    ob_start();
    require $page;
    $table = ob_get_contents();
    ob_end_clean();

    echo json_encode([
        "table" => $table,
        "last-update" => $last_update,
    ]);
    exit();
} catch (\Throwable $th) {
    http_response_code(500);
    exit();
}