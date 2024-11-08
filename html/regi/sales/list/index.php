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
    <title id="title">会計一覧</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/common/list.css">
    <style>
        .clickable-row {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/navbar.php"; ?>
    <!-- 残りのページ内容 -->
    <div class="container">
        <h2>売上記録</h2>
        <table>
            <thead>
            <tr>
                <th>商品名</th>
                <th>販売数</th>
                <th>売上</th>
            </tr>
            </thead>
            <tbody id="sales_table">
                <tr>
                    <td colspan="3">
                        <h2>読み込み中…</h2>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="container mt-4">
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <h1 class="text-center mb-4">会計一覧</h1>
        <p class="text-center my-3" style="font-size: 1.2em;">最終更新時刻:<span id="last-update">0000/0/0 00:00:00</span></p>
        <div class="text-center mb-3">
            <a href="../../" class="btn btn-outline-success btn-lg-custom p-2 mx-1">レジ画面へ</a>
            <div class="alert alert-info mt-2">
                詳細を見るには、項目を押してください。
            </div>
        </div>
        <!-- ページネーション -->
        <div>
            <button type="button" id="page_prev">&lt;</button>
            <span id="page_no">1</span> / <span id="page_end">1</span>
            <button type="button" id="page_next">&gt;</button>
        </div>
        <!-- ページネーション　終わり -->
        <table class="table table-success table-striped table-bordered table-hover text-center align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>購入日</th>
                    <th>詳細</th>
                    <th>会計者</th>
                </tr>
            </thead>
            <tbody id="accountants_table">
                <tr>
                    <td colspan="4">
                        <h2>読み込み中…</h2>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="notifications" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    <script src="/regi/notify/check_notify.js"></script>
    <script src="/common/set_tap_detail.js"></script>
    <script src="/common/check_update_common.js"></script>
    <script src="/common/pagination.js"></script>
</body>

</html>