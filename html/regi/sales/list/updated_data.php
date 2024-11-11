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
$_SESSION["regi"]["sales"]["list"]["last_update"] = $last_update;

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/sales/sale.php";
    $sale_obj = new Sale();
    $sales = $sale_obj->gets_range($offset, $limit);
    $page = "./no_list.php";
    if (!is_null($sales)) {
        $page = "./list.php";
    }
    ob_start();
    require $page;
    $accountants_table = ob_get_contents();
    ob_end_clean();

    $all_sales = $sale_obj->get_all();
    $sales_count = 0;
    if (!is_null($all_sales)) {
        $sales_count = ceil(count($all_sales) / $limit);
    }
    $page_end = $sales_count;

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
        "accountants_table" => $accountants_table,
        "sales_table" => $sales_table,
        "page_end" => $page_end,
        "last-update" => $last_update,
    ]);
    exit();
} catch (\Throwable $th) {
    http_response_code(500);
    exit();
}