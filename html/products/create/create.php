<?php
$item_name = htmlspecialchars($_POST["item_name"]);
$price = htmlspecialchars($_POST["price"]);
$item_image = $_FILES["item_image"]["tmp_name"];

require_once $_SERVER['DOCUMENT_ROOT'] . "/products/product.php";
session_start();
try {
    $product = new Product();
    $product->create($item_name, $price, $item_image, 0);

    // 成功メッセージをセッションに保存
    $_SESSION['message'] = '商品が正常に登録されました。';
    $_SESSION['message_type'] = 'success';

} catch (Exception $e) {
    // エラーメッセージをセッションに保存
    $_SESSION['message'] = 'エラーが発生しました: ' . $e->getMessage();
    $_SESSION['message_type'] = 'danger';
}
// 商品一覧ページへリダイレクト
header('Location: /products/list/');
exit();