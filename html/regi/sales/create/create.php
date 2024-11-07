<?php
session_start();

$ok = true;
$message = "";

if (!isset($_SESSION["regi"]["data"]["product_id"]) || $_SESSION["regi"]["data"]["product_id"] === "") {
    $message .= "「商品ID」";
    $ok = false;
}
if (!isset($_SESSION["regi"]["data"]["product_price"]) || $_SESSION["regi"]["data"]["product_price"] === "") {
    $message .= "「価格」";
    $ok = false;
}
if (!isset($_SESSION["regi"]["data"]["quantity"]) || $_SESSION["regi"]["data"]["quantity"] === "") {
    $message .= "「購入数」";
    $ok = false;
}
if (!isset($_SESSION["regi"]["data"]["subtotal"]) || $_SESSION["regi"]["data"]["subtotal"] === "") {
    $message .= "「小計」";
    $ok = false;
}
if (!isset($_SESSION["regi"]["data"]["total_amount"]) || $_SESSION["regi"]["data"]["total_amount"] === "") {
    $message .= "「合計購入数」";
    $ok = false;
}
if (!isset($_SESSION["regi"]["data"]["total_price"]) || $_SESSION["regi"]["data"]["total_price"] === "") {
    $message .= "「合計金額」";
    $ok = false;
}
if (!isset($_POST["received_price"]) || $_POST["received_price"] === "") {
    $message .= "「お預かり」";
    $ok = false;
}
if (!isset($_POST["returned_price"]) || $_POST["returned_price"] === "") {
    $message .= "「お釣り」";
    $ok = false;
}

if (!$ok) {
    $message .= "の入力項目が空になっています。";
    $_SESSION["message"] = $message;
    $_SESSION["message_type"] = "danger";
    session_write_close();
    header("Location: /regi/");
    exit();
}

$product_ids = $_SESSION["regi"]["data"]["product_id"];
$product_names = $_SESSION["regi"]["data"]["product_name"];
$product_prices = $_SESSION["regi"]["data"]["product_price"];
$quantities = $_SESSION["regi"]["data"]["quantity"];
$subtotals = $_SESSION["regi"]["data"]["subtotal"];

$total_amount = $_SESSION["regi"]["data"]["total_amount"];
$total_price = $_SESSION["regi"]["data"]["total_price"];

$received_price = $_POST["received_price"];
$returned_price = $_POST["returned_price"];

unset($_SESSION["regi"]["data"]);

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/users/user.php";
    $user_id = $_SESSION["login"]["user_id"];
    $user = new User();
    $user = $user->get_from_id($user_id);
    $accountant_user_name = $user->get_user_name();
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/sales/sale.php";
    $sale = new Sale();
    $sale->create($product_ids, $product_names, $product_prices, $quantities, $subtotals, $total_amount, $accountant_user_name, $total_price, $received_price, $returned_price);
    $id = $sale->get_accountant()->get_id();
    $_SESSION["message"] = "会計番号 {$id}番 で処理を完了しました。";
    $_SESSION["message_type"] = "success";
} catch (\Throwable $e) {
    $_SESSION["message"] = "エラーが発生しました。";
    $_SESSION["message_details"] = $e->getMessage();
    $_SESSION["message_type"] = "danger";
}

if (isset($_SESSION["regi"]["order"]["id"])) {
    session_write_close();
    header("Location: /regi/order/receive/receive.php");
} else {
    session_write_close();
    header("Location: /regi/");
}
exit();