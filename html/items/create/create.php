<?php
$item_name = htmlspecialchars($_POST["itenmane"]);
$price = htmlspecialchars($_POST["price"]);

$quantity = htmlspecialchars($_POST["quantity"]);

# 商品
require_once $_SERVER['DOCUMENT_ROOT'] . "/items/item.php";
$item = new Item();
$item = $item->create($item_name, $price);

# 在庫
require_once $_SERVER['DOCUMENT_ROOT'] . "/stocks/stock.php";
$stock = new Stock();
$stock = $stock->create($item->get_id(), $quantity);

if (!is_null($item) && !is_null($stock)) {
    header("Location: ./success.html");
} else {
    header("Location: ./fail.html");
}