<?php
session_start();

$id = htmlspecialchars($_POST["id"]);
require_once $_SERVER['DOCUMENT_ROOT'] . "/products/product.php";

$product = new Product();
$product = $product->get_from_item_id($id);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</head>
<body>
<h1 class="text-center">商品更新</h1>
<div class="col-7 mx-auto">
<table class="table table-info table-hover ">
    <form action="update.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $product->get_item()->get_id() ?>"></p>
        <tr class="form-group">
            <th class="aligin-middle">商品名</th>
            <td><input type="text" name="item_name" id="item_name" value="<?= $product->get_item()->get_item_name() ?>" class="form-control"></td>
        </tr>
        <tr class="form-group">
            <th class="aligin-middle">価格</th>
            <td><input type="number" name="price" id="price" value="<?= $product->get_item()->get_price() ?>" class="form-control"></td>
        </tr>
        <tr class="form-group">
            <th class="aligin-middle">商品イメージ</th>
            <td><img src="data:image/jpeg;base64,<?= $product->get_item()->get_item_image() ?>" alt="商品画像　ID<?= $product->get_item()->get_id() ?>番" id="now_item_image"></td>
        </tr>
        <tr class="form-group">
            <th class="aligin-middle">画像選択</th>
            <td><input type="file" name="new_item_image" id="new_item_image" accept="image/jpeg" class="uploadfile"></td>
        </tr>
        </table>
        <div class="text-center">
            <input type="submit" value="更新" class="btn btn-primary">
        </div>
    </form>
</div>
</body>
<script src="./set_now_image.js"></script>
</html>