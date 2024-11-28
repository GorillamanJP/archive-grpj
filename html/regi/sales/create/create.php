<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/verify_int_value.php";

session_start();

$back_url_success = isset($_SESSION["regi"]["order"]["id"]) ? "/regi/order/receive/receive.php" : "/regi/";
$back_url_fail = isset($_SESSION["regi"]["order"]["id"]) ? "/regi/order/list/" : "/regi/";

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
    redirect_with_error($back_url_fail, $message, "", "warning");
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

if (verify_int_value($total_amount, $total_price, $received_price, $returned_price) == false) {
    redirect_with_error($back_url_fail, "数値エラー", "入力内容のうちいずれかが小数になっているか、あまりにも大きい数になっている可能性があります。", "danger");
}
$checksum_txt = "";
foreach ($product_names as $name) {
    $checksum_txt .= $name;
}
foreach ($product_prices as $price) {
    $checksum_txt .= $price;
}
foreach ($quantities as $quantity) {
    $checksum_txt .= $quantity;
}
foreach ($subtotals as $subtotal) {
    $checksum_txt .= $subtotal;
}
$checksum_txt .= $total_amount;
$checksum_txt .= $total_price;
$checksum_txt .= $received_price;
$checksum_txt .= $returned_price;

$checksum = hash("sha256", $checksum_txt);

if (!isset($_POST["checksum"]) || $_POST["checksum"] != $checksum) {
    redirect_with_error($back_url_fail, "チェックサムエラーです。", "入力が改ざんされている可能性があります。", "danger");
}

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
    redirect_with_error($back_url_success, "会計番号 {$id}番 で処理を完了しました。", "", "success");
} catch (Throwable $e) {
    redirect_with_error($back_url_fail, "エラーが発生しました。", $e->getMessage(), "danger");
}