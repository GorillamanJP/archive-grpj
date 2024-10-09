<?php
session_start();

$id = htmlspecialchars($_POST["id"]);
$item_name = htmlspecialchars($_POST["item_name"]);
$price = htmlspecialchars($_POST["price"]);
$item_image = $_FILES["new_item_image"]["tmp_name"];

require_once $_SERVER['DOCUMENT_ROOT'] . "/items/item.php";

try {
    $item = new Item();
    $item = $item->get_from_id($id);
    $item = $item->update($item_name, $price, $item_image);
    // 成功時のメッセージをセッションに保存
    $_SESSION['message'] = "商品情報が正常に更新されました。";
    $_SESSION['message_type'] = "success";  // 成功メッセージ用
} catch (Exception $e) {
    // 失敗時のメッセージをセッションに保存
    $_SESSION['message'] = "エラーが発生しました: " . $e->getMessage();
    $_SESSION['message_type'] = "error";  // エラーメッセージ用
}
header("Location: /products/list/index.php");
exit();