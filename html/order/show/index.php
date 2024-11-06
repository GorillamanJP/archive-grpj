<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/protects/protect.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/is_not_order.php";
?>
<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/decrypt_id.php";

$order_id = decrypt_id($_COOKIE["order"]);
$order = new Order();
$order = $order->get_from_order_id($order_id);

if($order->get_order_order()->get_is_received()){
    session_write_close();
    header("Location: ../receive/");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注文表示</title>
</head>

<body>
    <h1>注文番号</h1>
    <?= $order_id ?>
    <h2>内容</h2>
    <table>
        <tr>
            <th>注文日</th>
            <td><?= $order->get_order_order()->get_date() ?></td>
        </tr>
        <tr>
            <th>内容</th>
            <td>
                <table>
                    <?php foreach ($order->get_order_details() as $detail): ?>
                        <tr>
                            <th>品名</th>
                            <td><?= $detail->get_item_name() ?></td>
                        </tr>
                        <tr>
                            <th>価格</th>
                            <td><?= $detail->get_item_price() ?></td>
                        </tr>
                        <tr>
                            <th>個数</th>
                            <td><?= $detail->get_quantity() ?></td>
                        </tr>
                        <tr>
                            <th>小計</th>
                            <td><?= $detail->get_subtotal() ?></td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </td>
        </tr>
        <tr>
            <th>総数</th>
            <td><?= $order->get_order_order()->get_total_amount() ?></td>
        </tr>
        <tr>
            <th>合計</th>
            <td><?= $order->get_order_order()->get_total_price() ?></td>
        </tr>
        <tr>
            <th>受け取り済み</th>
            <td><?= $order->get_order_order()->get_is_received() ? "はい" : "いいえ" ?></td>
        </tr>
    </table>
    <p>店頭にある表示用QRコードを使ってこの画面を再表示したうえで、店員にお見せください。</p>
</body>

</html>