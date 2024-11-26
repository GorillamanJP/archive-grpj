<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check_bool.php";
if (!login_check()) {
    http_response_code(403);
    exit();
}

session_start();

$last_update = date("Y/m/d H:i:s");
$_SESSION["regi"]["sales"]["list"]["last_update"] = $last_update;

try {
    require_once $_SERVER['DOCUMENT_ROOT']."/../classes/products/product.php";
    $product_obj = new Product();
    $products = $product_obj->get_all();
    $sales_page = "./no_sales_list.php";
    if(!is_null($products)){
        $sales_page = "./sales_list.php";
    }
    ob_start();
    require $sales_page;
    $sales_table = ob_get_contents();
    ob_end_clean();

    echo json_encode([
        "sales_table" => $sales_table,
        "last-update" => $last_update,
    ]);
    exit();
} catch (\Throwable $th) {
    http_response_code(500);
    exit();
}