<?php
require_once $_SERVER['DOCUMENT_ROOT']."/regi/users/login_check.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/sales/sale.php";
$sale_obj = new Sale();
$sales = $sale_obj->get_all();
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/items/item.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>会計リスト</h1>
    <?php if (is_null($sales)): ?>
        <p>会計記録はありません</p>
    <?php else: ?>
        <table>
            <?php foreach ($sales as $sale): ?>
                <tr>
                    <th>ID: <?= $sale->get_accountant()->get_id() ?></th>
                    <td><?= $sale->get_accountant()->get_date() ?></td>
                    <td>詳細→</td>
                    <td>
                        <table>
                            <?php foreach ($sale->get_details() as $detail): ?>
                                <tr>
                                    <?php $item_obj = new Item(); ?>
                                    <?php $item = $item_obj->get_from_id($detail->get_item_id()); ?>
                                    <td><?= $item->get_item_name() ?></td>
                                    <td><?= $detail->get_item_price() ?></td>
                                    <td><?= $detail->get_quantity() ?></td>
                                    <td><?= $detail->get_subtotal() ?></td>
                                </tr>
                            <?php endforeach ?>
                        </table>
                    </td>
                    <td><?= $sale->get_accountant()->get_total_price() ?></td>
                    <td><?= $sale->get_accountant()->get_total_amount() ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>
</body>

</html>