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

$dt = new DateTime();
$now = $dt->format("Y-m-d H:i:s.u");

$offset = htmlspecialchars($_POST["page_offset"]);
$limit = htmlspecialchars($_POST["page_limit"]);

$last_update = $now;
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
    $page_end = $sales_count > 0 ? $sales_count : 1;

    echo json_encode([
        "accountants_table" => $accountants_table,
        "page_end" => $page_end,
        "last-update" => date_create($last_update)->format("Y/m/d H:i:s"),
    ]);
    exit();
} catch (\Throwable $th) {
    http_response_code(500);
    exit();
}