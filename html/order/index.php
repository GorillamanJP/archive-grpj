<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/captcha/require_captcha.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/protects/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/is_receive.php";
session_start();
unset($_SESSION["order"]["order_items"]);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>モバイルオーダー</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/common/regi.css">
</head>

<body>
    <h1 class="text-center my-3">モバイルオーダー</h1>
    <div class="container regia">
        <div class="content1">
            <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
            <h1 class="text-center">商品一覧</h1>
            <p class="text-center">最終更新時刻:<span id="last-update">0000/0/0 00:00:00</span></p>
            <section class="image-text-block">
                <div class="product-grid" id="table">
                    <!-- ここに非同期で商品が読み込まれる -->
                    <p>読み込み中…</p>
                </div>
            </section>
        </div>
        <div class="content2">
            <h1 class="text-center">選択商品</h1>
            <table id="cart-table" class="table text-center">
                <thead>
                    <tr>
                        <th>商品名</th>
                        <th>価格</th>
                        <th>個数</th>
                        <th>取消</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- カート商品がここに追加される -->
                </tbody>
            </table>
        </div>
        <div class="content3">
            <h1 class="text-center">会計</h1>
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>合計品数</th>
                        <th>合計金額</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="total-count">0個</td>
                        <td id="total-price">0円</td>
                    </tr>
                </tbody>
            </table>
            <form action="./create/" method="post">
                <div id="form"></div>
                <input type="submit" value="確認へ進む→" class="btn btn-success">
            </form>
        </div>
    </div>
    <div id="notifications" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    <script src="/common/regi.js"></script>
    <script src="/common/check_update_common.js"></script>
</body>

</html>