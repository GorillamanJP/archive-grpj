<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";

$order_obj = new Order();
$orders = $order_obj->get_all();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注文一覧</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</head>

<body>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
    <h1>注文一覧</h1>
    <table>
        <tr>
            <th>注文番号</th>
            <th>注文日時</th>
            <th>詳細</th>
        </tr>
        <?php if (is_null($orders)): ?>
            <tr>
                <td colspan="3">
                    <h2>受け取り待ちの注文はありません。</h2>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order->get_order_order()->get_id() ?></td>
                    <td><?= $order->get_order_order()->get_date() ?></td>
                    <td>
                        <table>
                            <tr>
                                <th>品名</th>
                                <th>数量</th>
                            </tr>
                            <?php foreach ($order->get_order_details() as $detail): ?>
                                <tr>
                                    <td><?= $detail->get_item_name() ?></td>
                                    <td><?= $detail->get_quantity() ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </td>
                    <td>
                        <form action="../receive/" method="post">
                            <input type="hidden" name="order_id" id="order_id"
                                value="<?= $order->get_order_order()->get_id() ?>">
                            <button type="submit">受け取り</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>

</html>