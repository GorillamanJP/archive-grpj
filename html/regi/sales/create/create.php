<?php
$product_ids = $_POST["product_id"];
$quantities = $_POST["quantity"];

require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/sales/sale.php";
try {
    $sale = new Sale();
    $sale->create($product_ids, $quantities);
    echo "OK";
} catch (Exception $e) {
    echo "NG";
    echo $e->getMessage();
}