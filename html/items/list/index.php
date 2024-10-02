<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/items/item.php";
$item_obj = new Item();
$items = $item_obj->get_all();

require_once $_SERVER['DOCUMENT_ROOT'] . "/stocks/stock.php";
$stock_obj = new Stock();

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
    <?php if (is_null($items)): ?>
        <p>商品はありません</p>
    <?php else: ?>
        <table>
            <? foreach ($items as $item): ?>
                <tr>
                    <td><?= $item->get_item_name() ?></td>
                    <td><?= $item->get_price() ?></td>
                    <td><?= $stock_obj->get_from_item_id($item->get_id())->get_quantity() ?></td>
                    <td>
                        <form action="../update/" method="post">
                            <input type="hidden" name="id" id="id" value="<?= $item->get_id() ?>">
                            <input type="submit" value="更新">
                        </form>
                    </td>
                    <td>
                        <form action="../delete/" method="post">
                            <input type="hidden" name="id" id="id" value="<?= $item->get_id() ?>">
                            <input type="submit" value="削除">
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>
</body>

</html>