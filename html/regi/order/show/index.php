<?php
session_start();

if (!isset($_POST["order_id"]) || $_POST["order_id"] === "") {
    $_SESSION["message"] = "注文番号が指定されていません。";
    $_SESSION["message_type"] = "warning";
    session_write_close();
    header("Location: ../list/");
    exit();
}

$id = htmlspecialchars($_POST["order_id"]);

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
</head>

<body>
    <h1>注文詳細</h1>
    <table>
        <tr>
            <th>注文番号</th>
            <td><?= $order->get_order_order()->get_id() ?></td>
        </tr>
        <tr>
            <th>注文日</th>
            <td><?= $order->get_order_order()->get_date() ?></td>
        </tr>
        <tr>
            <th>内容</th>
            <td>
                <table>
                    <tr>
                        <th>品名</th>
                        <th>価格</th>
                        <th>個数</th>
                        <th>小計</th>
                    </tr>
                    <?php foreach ($order->get_order_details() as $detail): ?>
                        <tr>
                            <td><?= $detail->get_item_name() ?></td>
                            <td><?= $detail->get_item_price() ?></td>
                            <td><?= $detail->get_quantity() ?></td>
                            <td><?= $detail->get_subtotal() ?></td>
                        </tr>
                    <?php endforeach; ?>
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
    </table>
</body>

</html>