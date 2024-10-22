<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
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
    <link rel="stylesheet" href="/common/list.css">
    <style>
        .clickable-row {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <h1 class="text-center mb-4">会計一覧</h1>
        <div class="text-center mb-3">
            <a href="../../" class="btn btn-outline-success btn-lg-custom p-2 mx-1">レジ画面へ</a>
            <div class="alert alert-info mt-2">
                詳細を見るには、項目を押してください。
            </div>
        </div>
    
        <?php if (is_null($sales)): ?>
            <p class="text-center">会計記録はありません</p>
        <?php else: ?>
            <table class="table table-success table-striped table-bordered table-hover text-center align-middle">
                <tr>
                    <th>ID</th>
                    <th>購入日</th>
                    <th>詳細</th>
                    <th>合計金額</th>
                </tr>
                <?php foreach ($sales as $sale): ?>
                    <tr class="clickable-row" data-id="<?= $sale->get_accountant()->get_id() ?>">
                        <td><?= $sale->get_accountant()->get_id() ?></td>
                        <td><?= $sale->get_accountant()->get_date() ?></td>
                        <td>
                            <table class="table table-striped">
                                <tr>
                                    <th>品名</th>
                                    <th>価格</th>
                                    <th>数量</th>
                                    <th>小計</th>
                                </tr>
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
                    </tr>
                <?php endforeach ?>
            </table>
        <?php endif ?>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var rows = document.querySelectorAll(".clickable-row");
            rows.forEach(function (row) {
                row.addEventListener("click", function () {
                    var saleId = row.getAttribute("data-id");
                    var form = document.createElement("form");
                    form.setAttribute("method", "post");
                    form.setAttribute("action", "../show/");
                    var hiddenField = document.createElement("input");
                    hiddenField.setAttribute("type", "hidden");
                    hiddenField.setAttribute("name", "id");
                    hiddenField.setAttribute("value", saleId);
                    form.appendChild(hiddenField);
                    document.body.appendChild(form);
                    form.submit();
                });
            });
        });
    </script>
</body>

</html>