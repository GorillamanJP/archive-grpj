<?php
$id = htmlspecialchars($_POST["id"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/products/product.php";

$product = new Product();
$product = $product->get_from_item_id($id);
$product->delete();

echo "OK";