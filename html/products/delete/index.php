<?php
session_start();

$id = htmlspecialchars($_POST["id"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/products/product.php";

try {
    $product = new Product();
    $product = $product->get_from_item_id($id);
    $product->delete();
    // 成功メッセージをセッションに保存
    $_SESSION['message'] = '商品が正常に削除されました。';
    $_SESSION['message_type'] = 'success';
}catch(Exception $e){
    // エラーメッセージをセッションに保存
    $_SESSION['message'] = 'エラーが発生しました: ' . $e->getMessage();
    $_SESSION['message_type'] = 'danger';
}
// 商品一覧ページへリダイレクト
header('Location: /products/list/index.php');
exit();