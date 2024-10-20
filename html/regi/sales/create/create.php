<?php
session_start();

if (!isset($_SESSION["product_id"]) || $_SESSION["product_id"] === "") {
    $_SESSION["message"] = "商品が選ばれていません。";
    $_SESSION["message_type"] = "danger";
    header("Location: ../../");
}

if (!isset($_SESSION["quantity"]) || $_SESSION["quantity"] === "") {
    $_SESSION["message"] = "購入数が0の商品があります。";
    $_SESSION["message_type"] = "danger";
    header("Location: ../../");
}

$product_ids = $_POST["product_id"];
$product_prices = $_POST["product_price"];
$quantities = $_POST["quantity"];
$subtotals = $_POST["subtotal"];

$total_amount = $_POST["total_amount"];
$total_price = $_POST["total_price"];

$received_price = $_POST["received_price"];
$returned_price = $_POST["returned_price"];

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/sales/sale.php";
    $sale = new Sale();
    $sale->create($product_ids, $product_prices, $quantities, $subtotals, $total_amount, $total_price, $received_price, $returned_price);
    $_SESSION["message"] = "購入処理を正常に受け付けました。";
    $_SESSION["message_type"] = "success";

    $_SESSION["transactions"]["accountant_id"] = $sale->get_accountant()->get_id();
    $_SESSION["transactions"]["total_amount"] = $sale->get_accountant()->get_total_price();
    header("Location: ../../");
} catch (\Throwable $e) {
    $_SESSION["message"] = "エラーが発生しました。";
    $_SESSION["message_details"] = $e->getMessage();
    $_SESSION["message_type"] = "danger";
    header("./");
}