<?php
$id = htmlspecialchars($_POST["id"]);
require_once $_SERVER['DOCUMENT_ROOT'] . "/products/product.php";

$product = new Product();
$product = $product->get_from_stock_id($id);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>入荷処理</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</head>

<body>
    <h1 class="text-center">入荷処理</h1>
    <div class="col-7 mx-auto">
        <form action="update.php" method="post">
            <table class="table table-info table-hover">
                <tr class="form-group">
                    <th class="align-middle">商品名</th>
                    <td><?= $product->get_item()->get_item_name() ?></td>
                </tr>
                <tr class="form-group">
                    <th>現在の在庫数</th>
                    <td><?= $product->get_stock()->get_quantity() ?></td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">入荷数</th>
                    <td><input type="number" name="add_quantity" id="add_quantity" min="0" value="0" class="form-control"></td>
                </tr>
            </table>
            <input type="hidden" name="id" value="<?= $product->get_stock()->get_id() ?>"></p>
            <div class="text-center">
            <input type="submit" value="更新" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>

</html>