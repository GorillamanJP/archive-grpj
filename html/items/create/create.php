<?php
$itemname = htmlspecialchars($_POST["itenmane"]);
$price = htmlspecialchars($_POST["price"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/items/item.php";
$item = new Item();
$item = $item->create($itemname, $price);
if(!is_null($item)){
    header("Location: ./success.html");
} else {
    header("Location: ./fail.html");
}