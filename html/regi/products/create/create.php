<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/verify_int_value.php";

session_start();

$ok = true;
$message = "";

if (!isset($_POST["item_name"]) || $_POST["item_name"] === "") {
    $message .= "「商品名」";
    $ok = false;
}
if (!isset($_POST["price"]) || $_POST["price"] === "") {
    $message .= "「価格」";
    $ok = false;
}
if (!isset($_POST["add_quantity"]) || $_POST["add_quantity"] === "") {
    $message .= "「在庫」";
    $ok = false;
}
if (!isset($_FILES["item_image"]["tmp_name"]) || $_FILES["item_image"]["tmp_name"] === "") {
    $message .= "「商品画像」";
    $ok = false;
}

if (!$ok) {
    $message .= "の入力項目が空になっています。";
    redirect_with_error("./", $message, "", "warning");
}

$item_name = htmlspecialchars($_POST["item_name"]);
$price = htmlspecialchars($_POST["price"]);
$quantity = htmlspecialchars($_POST["add_quantity"]);
$item_image = $_FILES["item_image"]["tmp_name"];

if (verify_int_value($price, $quantity) == false) {
    redirect_with_error("./", "数値エラー", "価格または在庫の数値が小数になっているか、値が大きすぎる可能性があります。", "danger");
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/products/product.php";
try {
    $product = new Product();
    $product->create($item_name, $price, $item_image, $quantity);

    redirect_with_error("../list/", "商品が正常に登録されました。", "", "success");
} catch (Throwable $e) {
    redirect_with_error("./", "エラーが発生しました。", $e->getMessage(), "danger");
}