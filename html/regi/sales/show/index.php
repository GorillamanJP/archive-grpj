<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_POST["id"]) || $_POST["id"] === "") {
    $_SESSION["message"] = "会計番号が指定されていません。";
    $_SESSION["message_details"] = "このメッセージが出る場合、内部のバグの可能性がありますので、「何を」「どのように」したらエラーが出たのかを開発者までお伝えください。\nご不便をおかけして申し訳ありませんが、ご協力をお願いします。";
    $_SESSION["message_type"] = "danger";
    header("Location ../list/");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/sales/sale.php";

$sale = new Sale();
$sale = $sale->get_from_accountant_id($_POST["id"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/items/item.php";
$item_obj = new Item();

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会計情報</title>
</head>

<body>
    <h1>会計情報</h1>
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
    <table>
        <tr>
            <th>会計番号</th>
            <td><?= $sale->get_accountant()->get_id() ?></td>
        </tr>
        <tr>
            <th>会計日時</th>
            <td><?= $sale->get_accountant()->get_date() ?></td>
        </tr>
        <tr>
            <th>詳細</th>
            <td>
                <?php foreach ($sale->get_details() as $detail): ?>
                    <table>
                        <?php $item = $item_obj->get_from_id($detail->get_item_id()); ?>
                        <tr>
                            <th>商品名</th>
                            <td><?= $item->get_item_name() ?></td>
                        </tr>
                        <tr>
                            <th>価格</th>
                            <td><?= $detail->get_item_price() ?></td>
                        </tr>
                        <tr>
                            <th>購入数</th>
                            <td><?= $detail->get_quantity() ?></td>
                        </tr>
                        <tr>
                            <th>小計</th>
                            <td><?= $detail->get_subtotal() ?></td>
                        </tr>
                    </table>
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <th>合計購入数</th>
            <td><?= $sale->get_accountant()->get_total_amount() ?></td>
        </tr>
        <tr>
            <th>合計</th>
            <td><?= $sale->get_accountant()->get_total_price() ?></td>
        </tr>
        <tr>
            <th>お預かり</th>
            <td><?= $sale->get_transaction()->get_received_price() ?></td>
        </tr>
        <tr>
            <th>お釣り</th>
            <td><?= $sale->get_transaction()->get_returned_price() ?></td>
        </tr>
    </table>
</body>

</html>