<?php
session_start();
if (isset($_POST["item_name"], $_POST["price"], $_FILES["item_image"]["tmp_name"])) {
    $item_name = htmlspecialchars($_POST["item_name"]);
    $price = htmlspecialchars($_POST["price"]);
    $item_image = $_FILES["item_image"]["tmp_name"];

    require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/products/product.php";
    try {
        $product = new Product();
        $product->create($item_name, $price, $item_image, 0);

        // 成功メッセージをセッションに保存
        $_SESSION['message'] = '商品が正常に登録されました。';
        $_SESSION['message_type'] = 'success';

    } catch (\Throwable $e) {
        // エラーメッセージをセッションに保存
        $_SESSION['message'] = 'エラーが発生しました: ' . $e->getMessage();
        $_SESSION['message_type'] = 'danger';
    }
    // 商品一覧ページへリダイレクト
    header('Location: /regi/products/list/');
    exit();
} else {
    $_SESSION["message"] = "入力情報が不足しています。";
    $_SESSION["message_type"] = "danger";
    header("/regi/products/list/");
    exit();
}