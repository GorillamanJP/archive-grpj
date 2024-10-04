<?php
$id = htmlspecialchars($_POST["id"]);
$item_name = htmlspecialchars($_POST["item_name"]);
$price = htmlspecialchars($_POST["price"]);
$item_image = $_FILES["new_item_image"]["tmp_name"];

require_once $_SERVER['DOCUMENT_ROOT'] . "/items/item.php";

try {
    $item = new Item();
    $item = $item->get_from_id($id);
    $item = $item->update($item_name, $price, $item_image);
} catch (Exception $e) {
    echo $e->getMessage();
    echo "NG";
}
echo "OK";