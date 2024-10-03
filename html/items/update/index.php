<?php
$id = htmlspecialchars($_POST["id"]);
require_once $_SERVER['DOCUMENT_ROOT'] . "/items/item.php";

$item = new Item();
$item = $item->get_from_id($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="update.php" method="post">
        <input type="hidden" name="id" value="<?= $item->get_id() ?>"></p>
        <p><input type="text" name="item_name" id="item_name" value="<?= $item->get_item_name() ?>">商品名</p>
        <p><input type="number" name="price" id="price" value="<?= $item->get_price() ?>">価格</p>
        <input type="submit" value="更新">
    </form>
</body>
</html>