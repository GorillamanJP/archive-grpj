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

try {
    $order_id = decrypt_id($_COOKIE["order"]);
    $order = new Order();
    $order = $order->get_from_order_id($order_id);
} catch (Exception $e) {
    $_SESSION["order"]["warning"]["message"] = "注文番号が読み取れませんでした。";
    $_SESSION["order"]["warning"]["message_details"] = "クッキーに保存した注文番号が改ざんされた可能性があります。恐れ入りますが、店頭スタッフまでお尋ねください。";
    session_write_close();
    header("Location: /order/error/");
    exit();
}
if ($order->get_order_order()->get_is_received()) {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</head>

<body>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
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