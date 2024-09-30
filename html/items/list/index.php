<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/items/item.php";
$item_obj = new Item();
$items = $item_obj->get_all();
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
                    <td><a href="../update/?id=<?= $item->get_id() ?>">ID: <?= $item->get_id() ?></a></td>
                    <td><?= $item->get_itemname() ?></td>
                    <td><?= $item->get_price() ?></td>
                    <td><a href="../delete/?id=<?= $item->get_id() ?>">削除</a></td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>
</body>

</html>