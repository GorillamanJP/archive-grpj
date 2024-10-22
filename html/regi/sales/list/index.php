<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/sales/sale.php";
$sale_obj = new Sale();
$sales = $sale_obj->get_all();
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/items/item.php";
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会計履歴</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }

        h1 {
            font-weight: bold;
            color: #333;
            margin-top: 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        .table {
            margin-top: 20px;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .round-button{
            border-radius: 2em;
        }

        .btn-lg-custom {
            padding: 1em 2em;
            font-size: 1.2em;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center">会計リスト</h1>
        <div class="text-center my-3">
            <a href="../../" class="btn btn-outline-success btn-lg-custom p-2 mx-1">レジ画面へ</a>
        </div>
        <?php if (is_null($sales)): ?>
            <p class="text-center">会計記録はありません</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>購入日</th>
                            <th>
                                <table class="table">
                                    <tr>
                                        <th>商品名</th>
                                        <th>価格</th>
                                        <th>購入数</th>
                                        <th>小計</th>
                                    </tr>
                                </table>
                            </th>
                            <th>合計金額</th>
                            <th>合計購入数</th>
                            <th>詳細</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sales as $sale): ?>
                            <tr>
                                <td><?= $sale->get_accountant()->get_id() ?></td>
                                <td><?= $sale->get_accountant()->get_date() ?></td>
                                <td>
                                    <table class="table">
                                        <?php foreach ($sale->get_details() as $detail): ?>
                                            <tr>
                                                <?php $item_obj = new Item(); ?>
                                                <?php $item = $item_obj->get_from_id($detail->get_item_id()); ?>
                                                <td><?= $item->get_item_name() ?></td>
                                                <td><?= $detail->get_item_price() ?></td>
                                                <td><?= $detail->get_quantity() ?></td>
                                                <td><?= $detail->get_subtotal() ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </table>
                                </td>
                                <td><?= $sale->get_accountant()->get_total_price() ?></td>
                                <td><?= $sale->get_accountant()->get_total_amount() ?></td>
                                <td>
                                    <form action="../show/" method="post">
                                        <input type="hidden" name="id" id="id" value="<?= $sale->get_accountant()->get_id() ?>">
                                        <input type="submit" value="詳細表示" class="btn btn-outline-primary round-button">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
    </div>
</body>

</html>