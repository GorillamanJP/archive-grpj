<?php
session_start();

if (!isset($_POST["id"]) || $_POST["id"] === "") {
    $_SESSION["message"] = "注文番号が指定されていません。";
    $_SESSION["message_type"] = "warning";
    session_write_close();
    header("Location: ../list/");
    exit();
}

$id = htmlspecialchars($_POST["id"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";

try {
    $order = new Order();
    $order = $order->get_from_order_id($id);
} catch (Exception $e) {
    $_SESSION["message"] = "指定した注文はありません。";
    $_SESSION["message_details"] = "多分バグです。開発者までお問い合わせください。";
    $_SESSION["message_type"] = "danger";
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注文詳細</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/common/list.css">
    <link rel="stylesheet" href="/common/create.css">
</head>

<body>
    <div class="container">
        <h1 class="text-center my-3">注文詳細</h1>
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <table class="table table-striped table-primary">
            <tr>
                <th class="text-end">注文番号</th>
                <td><?= $order->get_order_order()->get_id() ?></td>
            </tr>
            <tr>
                <th class="text-end">注文日</th>
                <td><?= $order->get_order_order()->get_date() ?></td>
            </tr>
            <tr>
                <th class="text-center" colspan="2">注文内容</th>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th>品名</th>
                                <th>価格</th>
                                <th>個数</th>
                                <th>小計</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order->get_order_details() as $detail): ?>
                                <tr>
                                    <td><?= $detail->get_item_name() ?></td>
                                    <td>¥<?= $detail->get_item_price() ?></td>
                                    <td><?= $detail->get_quantity() ?></td>
                                    <td>¥<?= $detail->get_subtotal() ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <th class="text-end">総数</th>
                <td><?= $order->get_order_order()->get_total_amount() ?></td>
            </tr>
            <tr>
                <th class="text-end">合計</th>
                <td>¥<?= $order->get_order_order()->get_total_price() ?></td>
            </tr>
        </table>
        <div class="text-center">
            <a href="../list/" class="btn btn-outline-secondary btn-lg mb-4">戻る</a>
        </div>
    </div>
</body>

</html>
