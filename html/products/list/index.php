<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/products/product.php";
$product_obj = new Product();
$products = $product_obj->get_all();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>商品一覧</h1>
    <?php if (is_null($products)): ?>
        <p>商品はありません</p>
    <?php else: ?>
        <table>
            <? foreach ($products as $product): ?>
                <tr>
                    <td><?= $product->get_item()->get_item_name() ?></td>
                    <td><?= $product->get_item()->get_price() ?></td>
                    <td><?= $product->get_stock()->get_quantity() ?></td>
                    <td><img src="data:image/jpeg;base64,<?= $product->get_item()->get_item_image() ?>" alt="商品画像　ID<?= $product->get_item()->get_id() ?>番"></td>
                    <td>
                        <form action="../update/item/" method="post">
                            <input type="hidden" name="id" id="id" value="<?= $product->get_item()->get_id() ?>">
                            <input type="submit" value="商品更新">
                        </form>
                    </td>
                    <td>
                        <form action="../update/stock/" method="post">
                            <input type="hidden" name="id" id="id" value="<?= $product->get_stock()->get_id() ?>">
                            <input type="submit" value="入荷処理">
                        </form>
                    </td>
                    <td>
                        <form action="../delete/" method="post">
                            <input type="hidden" name="id" id="id" value="<?= $product->get_item()->get_id() ?>">
                            <input type="submit" value="削除">
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>
</body>

</html>