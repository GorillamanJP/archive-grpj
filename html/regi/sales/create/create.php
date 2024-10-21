<?php
if (!isset($_SESSION)) {
    session_start();
}

$ok = true;
$message = "";

if(!isset($_POST["product_id"]) || $_POST["product_id"] === ""){
    $message .= "「商品ID」";
}
if(!isset($_POST["product_price"]) || $_POST["product_price"] === ""){
    $message .= "「価格」";
}
if(!isset($_POST["quantity"]) || $_POST["quantity"] === ""){
    $message .= "「購入数」";
}
if(!isset($_POST["subtotal"]) || $_POST["subtotal"] === ""){
    $message .= "「小計」";
}
if(!isset($_POST["total_amount"]) || $_POST["total_amount"] === ""){
    $message .= "「合計購入数」";
}
if(!isset($_POST["total_price"]) || $_POST["total_price"] === ""){
    $message .= "「合計金額」";
}
if(!isset($_POST["received_price"]) || $_POST["received_price"] === ""){
    $message .= "「お預かり」";
}
if(!isset($_POST["returned_price"]) || $_POST["returned_price"] === ""){
    $message .= "「お釣り」";
}

if(!$ok){
    $_SESSION["message"] = $message;
    $_SESSION["message_type"] = "danger";
    header("Location: /regi/");
    exit();
}

$product_ids = $_POST["product_id"];
$product_prices = $_POST["product_price"];
$quantities = $_POST["quantity"];
$subtotals = $_POST["subtotal"];

$total_amount = $_POST["total_amount"];
$total_price = $_POST["total_price"];

$received_price = $_POST["received_price"];
$returned_price = $_POST["returned_price"];

if($returned_price < 0){
    $_SESSION["message"] = "お預かり金額が不足しています。";
    $_SESSION["message_type"] = "warning";
    header("Location: ./");
    exit();
}

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