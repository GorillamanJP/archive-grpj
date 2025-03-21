<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/protects/protect.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/is_not_order.php";
?>
<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";
require_once
    $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/decrypt_id.php";
try {
    $order_id = decrypt_id(htmlspecialchars($_COOKIE["order"]));
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
if ($order->get_order_order()->get_is_cancel()) {
    session_write_close();
    header("Location: ../cancel/");
    exit();
}

$ios = preg_match('/(iPhone|iPad|iPod|Android)/', $_SERVER['HTTP_USER_AGENT']);
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

        .call_flash {
            animation: flash 1s infinite;
        }

        @keyframes flash {
            0% {
                background-color: yellow;
            }

            50% {
                background-color: transparent;
            }

            100% {
                background-color: yellow;
            }
        }
    </style>
</head>

<body class="pt-0">
    <div class="container">
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <h1 class="text-center mb-3">注文番号：<?= $order_id ?></h1>
        <h2 class="text-center my-3">注文内容</h2>
        <table class="table table-striped table-success">
            <tr>
                <th class="text-end">注文日</th>
                <td><?= $order->get_order_order()->get_date() ?></td>
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
                                <td>&yen;<?= $detail->get_item_price() ?></td>
                                <td><?= $detail->get_quantity() ?></td>
                                <td>&yen;<?= $detail->get_subtotal() ?></td>
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
                <td>&yen;<?= $order->get_order_order()->get_total_price() ?></td>
            </tr>
        </table>
        <div id="call_status" class="container p-5 call_flash" style="display: none;">
            <h1 class="text-center py-5 my-5">呼び出されています！</h1>
        </div>
        <div class="alert alert-info alert-custom mt-3" role="alert">
            <p class="text-center mb-3">この画面を開いたままにしておくと、呼び出しが分かって便利です。</p>
            <?php if ($ios): ?>
                <div class="container alert alert-warning alert-custom">
                    <h4>iPhone/iPadご利用の方へ</h4>
                    <p>通知を受け取るためには、ホーム画面へのブックマークが必要です。</p>
                    <p>右上の <i class="bi bi-box-arrow-up"></i> から、"ホーム画面に追加"を押してください。</p>
                    <p class="mb-1">その後、ホーム画面に追加された"モバイルオーダー"を開いてください。</p>
                </div>
            <?php endif ?>
            <div class="d-flex justify-content-center align-items-center my-2">
                <button class="btn btn-success" type="button" id="notify_button">プッシュ通知を<span id="notify_enable_text">有効</span>にする</button>
            </div>
            <p>状態: <span id="notify_status_text">許可が必要</span></p>
        </div>
        <div class="alert alert-danger alert-custom" role="alert">
            <span class="text-center">長時間受け取りに来られない場合、オーダーをキャンセルさせていただく場合がございます。</span>
        </div>
    </div>
    <script src="./check_receive.js"></script>
    <script src="./check_call.js"></script>
    <script src="./index.js"></script>
</body>

</html>