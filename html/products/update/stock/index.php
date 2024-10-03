<?php
$id = htmlspecialchars($_POST["id"]);
require_once $_SERVER['DOCUMENT_ROOT'] . "/products/product.php";

$product = new Product();
$product = $product->get_from_item_id($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>TODO: 入荷の処理として何か実装したい</h1>
    <form action="update.php" method="post">
        <input type="hidden" name="id" value="<?= $product->get_item()->get_id() ?>"></p>
        <p><input type="text" name="item_name" id="item_name" value="<?= $product->get_item()->get_item_name() ?>">商品名</p>
        <p><input type="number" name="quantity" id="quantity" value="<?= $product->get_stock()->get_quantity() ?>">在庫数</p>
        <input type="submit" value="更新">
    </form>
</body>
</html>