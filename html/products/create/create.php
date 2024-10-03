<?php
$item_name = htmlspecialchars($_POST["item_name"]);
$price = htmlspecialchars($_POST["price"]);
$item_image = $_FILES["item_image"]["tmp_name"];
$quantity = htmlspecialchars($_POST["quantity"]);

if (create($item_name, $price, $item_image, $quantity)) {
    echo "OK";
} else {
    echo "NG";
}

function create($item_name, $price, $item_image, $quantity): bool
{
    require_once $_SERVER['DOCUMENT_ROOT'] . "/products/product.php";
    $product = new Product();
    $product = $product->create($item_name, $price, $item_image, $quantity);

    if (!is_null($product)) {
        return true;
    } else {
        return false;
    }
}