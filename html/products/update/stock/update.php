<?php
$id = htmlspecialchars($_POST["id"]);
$add_quantity = htmlspecialchars($_POST["add_quantity"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/stocks/stock.php";

try {
    $stock = new Stock();
    $stock->start_transaction();
    $stock = $stock->get_from_id($id);
    $now_quantity = $stock->get_quantity();
    $stock = $stock->update($now_quantity + $add_quantity);
    $stock->commit();
    echo "OK";
} catch (Exception $e) {
    echo "NG";
}
