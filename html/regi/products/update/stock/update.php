<?php
$id = htmlspecialchars($_POST["id"]);
$add_quantity = htmlspecialchars($_POST["add_quantity"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/stocks/stock.php";

session_start();

try {
    $stock = new Stock();
    $stock->start_transaction();
    $stock = $stock->get_from_id($id);
    $now_quantity = $stock->get_quantity();
    // 在庫が0未満にならないようにチェック
    $new_quantity = $now_quantity + $add_quantity;
    if ($new_quantity < 0) {
        throw new Exception("在庫が0未満になるため、更新できません。");
    }

    $stock = $stock->update($new_quantity);
    $stock->commit();
    $_SESSION["message"] = "在庫が追加されました。";
    $_SESSION["message_type"] = "success";
    header("Location: /regi/products/list/index.php");
} catch (Exception $e) {
    $_SESSION["message"] = $e->getMessage();
    $_SESSION["message_type"] = "danger";
    header("Location: /regi/products/list/index.php");
}
