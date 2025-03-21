<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
session_start();
if (!isset($_POST["id"]) || $_POST["id"] === "") {
    $_SESSION["message"] = "会計番号が指定されていません。";
    $_SESSION["message_details"] = "このメッセージが出る場合、内部のバグの可能性がありますので、「何を」「どのように」したらエラーが出たのかを開発者までお伝えください。\nご不便をおかけして申し訳ありませんが、ご協力をお願いします。";
    $_SESSION["message_type"] = "danger";
    session_write_close();
    header("Location ../list/");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/sales/sale.php";

$sale = new Sale();
$sale = $sale->get_from_accountant_id(htmlspecialchars($_POST["id"]));

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/items/item.php";
$item_obj = new Item();

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レジ/会計管理/会計情報</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <link rel="stylesheet" href="/common/list.css">
    <link rel="stylesheet" href="/common/create.css">
</head>

<body>
    <div class="container">
        <h1 class="text-center my-3">会計詳細</h1>
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <table class="table table-hover">
            <tr>
                <th class="text-end">会計番号</th>
                <td><?= $sale->get_accountant()->get_id() ?></td>
            </tr>
            <tr>
                <th class="text-end">会計日時</th>
                <td><?= $sale->get_accountant()->get_formatted_date() ?></td>
            </tr>
            <tr>
                <th colspan="2" class="text-center">購入一覧</th>
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
                        <?php foreach ($sale->get_details() as $detail): ?>
                            <tr>
                                <td><?= $detail->get_item_name() ?></td>
                                <td>&#165;<?= $detail->get_item_price() ?></td>
                                <td><?= $detail->get_quantity() ?></td>
                                <td>&#165;<?= $detail->get_subtotal() ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </td>
            </tr>
            <tr>
                <th class="text-end">合計購入数</th>
                <td><?= $sale->get_accountant()->get_total_amount() ?></td>
            </tr>
            <tr>
                <th class="text-end">合計</th>
                <td>&#165;<?= $sale->get_accountant()->get_total_price() ?></td>
            </tr>
            <tr>
                <th class="text-end">お預かり</th>
                <td>&#165;<?= $sale->get_transaction()->get_received_price() ?></td>
            </tr>
            <tr>
                <th class="text-end">お釣り</th>
                <td>&#165;<?= $sale->get_transaction()->get_returned_price() ?></td>
            </tr>
            <tr>
                <th class="text-end">会計者</th>
                <td><?= $sale->get_accountant()->get_accountant_user_name() ?></td>
            </tr>
        </table>
        <div class="text-center">
            <a href="../list/" class="btn btn-outline-secondary btn-lg mb-4">戻る</a>
        </div>
    </div>
</body>

</html>