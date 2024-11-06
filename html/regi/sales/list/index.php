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
        <p>そのうち自動更新されるようになります</p>
        <table>
            <tr>
                <th>商品名</th>
                <th>販売数</th>
                <th>売上</th>
            </tr>
            <?php if (is_null($products)): ?>
                <h3>会計記録はありません。</h3>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $product->get_item_name() ?></td>
                        <td><?= $detail->get_total_sold($product->get_item_id()) ?></td>
                        <td><?= $detail->get_total_revenue($product->get_item_id()) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
    <p>ページネーションつけたい</p>
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
        <table class="table table-success table-striped table-bordered table-hover text-center align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>購入日</th>
                    <th>詳細</th>
                    <th>会計者</th>
                </tr>
            </thead>
            <tbody id="refresh">
                <input type="hidden" id="update_msg" name="update_msg" value="情報">
                <tr>
                    <td colspan="4">
                        <h2>読み込み中…</h2>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="notifications" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    <script src="/common/check_notify.js"></script>
    <script src="./check_update.js"></script>
</body>

</html>