<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_alert_with_form.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/verify_int_value.php";

session_start();

if (!isset($_POST["id"]) || $_POST["id"] === "") {
    redirect_with_error("../../list/", "商品IDが指定されていません。", "", "warning");
}

$id = htmlspecialchars($_POST["id"]);

$ok = true;
$message = "";
if (!isset($_POST["item_name"]) || $_POST["item_name"] === "") {
    # 商品名
    $message .= "「商品名」";
    $ok = false;
}
if (!isset($_POST["price"]) || $_POST["price"] === "") {
    # 価格
    $message .= "「価格」";
    $ok = false;
}
if (!isset($_FILES["new_item_image"]["tmp_name"]) || $_FILES["new_item_image"]["tmp_name"] === "") {
    # 画像
    $message .= "「商品画像」";
    $ok = false;
}

if (!$ok) {
    $message .= "の入力項目が空になっています。";
    redirect_with_error_with_form("./", $message, null, "warning", $_POST);
}

$item_name = htmlspecialchars($_POST["item_name"]);
$price = htmlspecialchars($_POST["price"]);
$item_image = $_FILES["new_item_image"]["tmp_name"];

if (verify_int_value($price) == false) {
    redirect_with_error_with_form("./", "数値エラー", "価格または在庫の数値が小数になっているか、値が大きすぎる可能性があります。", "danger", $_POST);
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/items/item.php";

try {
    $item = new Item();
    $item = $item->get_from_id($id);
    $item = $item->update($item_name, $price, $item_image);

    redirect_with_error("../../list/", "商品情報が正常に更新されました。", "", "success");
} catch (Throwable $e) {
    redirect_with_error_with_form("./", "エラーが発生しました。", $e->getMessage(), "danger", $_POST);
}