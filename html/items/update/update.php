<?php
$id = htmlspecialchars($_POST["id"]);
$itemname = htmlspecialchars($_POST["itemname"]);
$price = htmlspecialchars($_POST["price"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/items/item.php";
$item = new Item();
$item = $item->get_from_id($id);
$item = $item->update($itemname, $price);
if($item){
    echo "OK";
} else {
    echo "Fail";
}