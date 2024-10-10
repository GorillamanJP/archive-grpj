<?php
session_start();
if (isset($_POST["id"], $_POST["item_name"], $_POST["price"], $_FILES["new_item_image"]["tmp_name"])) {
    $id = htmlspecialchars($_POST["id"]);
    $item_name = htmlspecialchars($_POST["item_name"]);
    $price = htmlspecialchars($_POST["price"]);
    $item_image = $_FILES["new_item_image"]["tmp_name"];

    require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/items/item.php";

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
} else {
    $_SESSION["message"] = "入力情報が不足しています。";
    $_SESSION["message_type"] = "error";
}
header("Location: /regi/products/list/");
exit();