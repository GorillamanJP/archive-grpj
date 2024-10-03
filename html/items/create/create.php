<?php
$item_name = htmlspecialchars($_POST["itenmane"]);
$price = htmlspecialchars($_POST["price"]);
$item_image = $_FILES["item_image"]["tmp_name"];

if (create($item_name, $price, $item_image)) {
}

function create($item_name, $price, $item_image): bool
{
    # 商品
    require_once $_SERVER['DOCUMENT_ROOT'] . "/items/item.php";
    $item = new Item();
    $item = $item->create($item_name, $price, $item_image);

    if (!is_null($item)) {
        return true;
    } else {
        return false;
    }
}