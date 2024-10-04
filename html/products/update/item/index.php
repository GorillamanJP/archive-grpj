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
    <form action="update.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $product->get_item()->get_id() ?>"></p>
        <p><input type="text" name="item_name" id="item_name" value="<?= $product->get_item()->get_item_name() ?>">商品名</p>
        <p><input type="number" name="price" id="price" value="<?= $product->get_item()->get_price() ?>">価格</p>
        <p><img src="data:image/jpeg;base64,<?= $product->get_item()->get_item_image() ?>" alt="商品画像　ID<?= $product->get_item()->get_id() ?>番" id="now_item_image"></p>
        <p><input type="file" name="new_item_image" id="new_item_image" accept="image/jpeg"></p>
        <input type="submit" value="更新">
    </form>
</body>
<script src="./set_now_image.js"></script>
</html>