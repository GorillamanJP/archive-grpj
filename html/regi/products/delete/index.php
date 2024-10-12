<?php
session_start();

$id = htmlspecialchars($_POST["id"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/products/product.php";

try {
    $product = new Product();
    $product = $product->get_from_item_id($id);
    $product->delete();
    // 成功メッセージをセッションに保存
    $_SESSION['message'] = '商品が正常に削除されました。';
    $_SESSION['message_type'] = 'success';
}catch(\Throwable $e){
    // エラーメッセージをセッションに保存
    $_SESSION['message'] = 'エラーが発生しました: ' . $e->getMessage();
    $_SESSION['message_type'] = 'danger';
}
// 商品一覧ページへリダイレクト
header('Location: /regi/products/list/index.php');
exit();