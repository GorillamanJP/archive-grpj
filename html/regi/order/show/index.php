<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";

session_start();

if (!isset($_POST["id"]) || $_POST["id"] === "") {
    redirect_with_error("../list/", "注文番号が指定されていません。", "", "warning");
}

$id = htmlspecialchars($_POST["id"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";

try {
    $order = new Order();
    $order = $order->get_from_order_id($id);
} catch (Throwable $e) {
    redirect_with_error("../list/", "エラーが発生しました。", $e->getMessage(), "danger");
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注文詳細</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
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
            <tr>
                <th>呼び出し</th>
                <td><?= $order->get_order_order()->get_is_call() ? "呼び出し中" : "-" ?></td>
            </tr>
            <tr>
                <th>キャンセル</th>
                <td><?= $order->get_order_order()->get_is_cancel() ? "キャンセル" : "-" ?></td>
            </tr>
            <tr>
                <th>受け取り</th>
                <td><?= $order->get_order_order()->get_is_received() ? "済" : "-" ?></td>
            </tr>
        </table>
        <div class="text-center">
            <a href="../list/" class="btn btn-outline-secondary btn-lg mb-4">戻る</a>
        </div>
    </div>
</body>

</html>