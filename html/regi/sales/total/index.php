<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/products/product.php";
$product = new Product();
$products = $product->get_all();

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/details/detail.php";
$detail = new Detail();

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="title">レジ/会計管理/総売上記録</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <link rel="stylesheet" href="/common/list.css">
    <style>
        .clickable-row {
            cursor: pointer;
        }

        .custom-background {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .loading-message {
            color: #888;
            font-style: italic;
        }
    </style>
</head>

<body>
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/navbar.php"; ?>
    <div class="container custom-background">
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <h1 class="text-center mb-4">総売上記録</h1>
        <p class="text-center my-3" style="font-size: 1.2em;">最終更新時刻:<span id="last-update">0000/0/0 00:00:00</span></p>
        <div class="text-center mb-3">
            <a href="../list/" class="btn btn-outline-primary btn-lg-custom p-2 mx-1">会計一覧</a>
            <a href="../../" class="btn btn-outline-success btn-lg-custom p-2 mx-1">レジ画面</a>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>商品名</th>
                            <th>販売数</th>
                            <th>売上</th>
                        </tr>
                    </thead>
                    <tbody id="sales_table">
                        <tr>
                            <td colspan="3" class="text-center loading-message">
                                <h2>読み込み中…</h2>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="notifications" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
        <script>
            function run_custom_function() {
                set_tap_detail();
            }
        </script>
        <script src="/regi/notify/check_notify.js"></script>
        <script src="/common/set_tap_detail.js"></script>
        <script src="/common/check_update_common.js"></script>
</body>

</html>