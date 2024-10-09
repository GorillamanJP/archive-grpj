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
    $stock = $stock->update($now_quantity + $add_quantity);
    $stock->commit();
    $_SESSION["message"] = "在庫が追加されました。";
    $_SESSION["message_type"] = "success";
    header("Location: /products/list/index.php");
} catch (Exception $e) {
    $_SESSION["message"] = "在庫の追加に失敗しました。";
    $_SESSION["message_type"] = "error";
    header("Location: /regi/products/list/index.php");
}
