<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";

session_start();

if (!isset($_POST["id"]) || $_POST["id"] === "") {
    redirect_with_error("../regi/", "商品IDが指定されていません。", "", "warning");
}

$id = htmlspecialchars($_POST["id"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/products/product.php";

try {
    $product = new Product();
    $product = $product->get_from_item_id($id);
    $product->delete();

    redirect_with_error("../list/", "商品が正常に削除されました。", "", "success");
} catch (Throwable $e) {
    redirect_with_error("../list/", "エラーが発生しました。", $e->getMessage(), "danger");
}