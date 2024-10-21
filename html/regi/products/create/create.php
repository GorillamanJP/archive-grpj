<?php
session_start();

$ok = true;
$_SESSION["message"] = "";

if(!isset($_POST["item_name"]) || $_POST["item_name"] === ""){
    $_SESSION["message"] .= "「商品名」";
    $ok = false;
}
if(!isset($_POST["price"]) || $_POST["price"] === ""){
    $_SESSION["message"] .= "「価格」";
    $ok = false;
}
if(!isset($_FILES["item_image"]["tmp_name"]) || $_FILES["item_image"]["tmp_name"] === ""){
    $_SESSION["message"] .= "「商品画像」";
    $ok = false;
}

if ($ok) {
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
        session_write_close();
        header("Location: ../list/");
        exit();
    } catch (\Throwable $e) {
        // エラーメッセージをセッションに保存
        $_SESSION['message'] = 'エラーが発生しました。';
        $_SESSION["message_details"] = $e->getMessage();
        $_SESSION['message_type'] = 'danger';
    }
} else {
    $_SESSION["message"] = "の項目が空になっています。";
    $_SESSION["message_type"] = "danger";
}
session_write_close();
header("Location: ./");