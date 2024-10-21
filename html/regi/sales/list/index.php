<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/sales/sale.php";
$sale_obj = new Sale();
$sales = $sale_obj->get_all();
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/items/item.php";
?>
<!DOCTYPE html>
<html lang="ja">

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
            <tr>
                <th>ID</th>
                <th>購入日</th>
                <th>
                    <span>詳細</span>
                    <table>
                        <tr>
                            <th>商品名</th>
                            <th>価格</th>
                            <th>購入数</th>
                            <th>小計</th>
                        </tr>
                    </table>
                </th>
                <th>合計金額</th>
                <th>合計購入数</th>
                <th></th>
            </tr>
            <?php foreach ($sales as $sale): ?>
                <tr>
                    <td><?= $sale->get_accountant()->get_id() ?></td>
                    <td><?= $sale->get_accountant()->get_date() ?></td>
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
                    <td>
                        <form action="../show/" method="post">
                            <input type="hidden" name="id" id="id" value="<?= $sale->get_accountant()->get_id() ?>">
                            <input type="submit" value="詳細表示">
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>
</body>

</html>