<?php
$item_name = htmlspecialchars($_POST["item_name"]);
$price = htmlspecialchars($_POST["price"]);
$item_image = $_FILES["item_image"]["tmp_name"];

require_once $_SERVER['DOCUMENT_ROOT'] . "/products/product.php";
try {
    $product = new Product();
    $product->create($item_name, $price, $item_image, 0);
} catch (Exception $e) {
    echo $e->getMessage();
    echo "NG";
}
echo "OK";