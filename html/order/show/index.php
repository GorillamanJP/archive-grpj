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
if($order->get_order_order()->get_is_cancel()){
    session_write_close();
    header("Location: ../cancel/");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>モバイルオーダー/注文表示</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <link rel="stylesheet" href="/common/list.css">
    <link rel="stylesheet" href="/common/create.css">
    <style>
        .alert-custom {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <h1 class="text-center my-3">注文番号：<?= $order_id ?></h1>
        <h2 class="text-center my-3">注文内容</h2>
        <table class="table table-striped table-success">
            <tr>
                <th class="text-end">注文日</th>
                <td><?= $order->get_order_order()->get_date() ?></td>
            </tr>
            <tr>
                <th colspan="2" class="text-center">注文内容</th>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="table text-center">
                        <tr>
                            <th>商品名</th>
                            <th>価格</th>
                            <th>購入数</th>
                            <th>小計</th>
                        </tr>
                        <?php foreach ($order->get_order_details() as $detail): ?>
                            <tr>
                                <td><?= $detail->get_item_name() ?></td>
                                <td><?= $detail->get_item_price() ?></td>
                                <td><?= $detail->get_quantity() ?></td>
                                <td><?= $detail->get_subtotal() ?></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                </td>
            </tr>
            <tr>
                <th class="text-end">合計購入数</th>
                <td><?= $order->get_order_order()->get_total_amount() ?></td>
            </tr>
            <tr>
                <th class="text-end">合計</th>
                <td><?= $order->get_order_order()->get_total_price() ?></td>
            </tr>
            <tr>
                <th class="text-end">呼び出し（これは分離して目立つようにしてほしい）</th>
                <td id="call_status">-</td>
            </tr>
        </table>
    </div>
    <div class="container mt-4">
        <div class="alert alert-info alert-custom" role="alert">
            <p class="text-center">この画面を開いたままにしておくと、出来上がりが分かって便利です。</p>
            <p class="text-center">通知音を鳴らす<input type="checkbox" name="allow_sound" id="allow_sound"></p>
        </div>
        <div class="alert alert-danger alert-custom" role="alert">
            <p class="text-center">長時間受け取りに来られない場合、オーダーをキャンセルさせていただく場合がございます。</p>
        </div>
    </div>
</body>
<audio id="notificationSound" src="./bell.wav" preload="auto"></audio>
<script src="./check_receive.js"></script>
<script src="./check_call.js"></script>

</html>