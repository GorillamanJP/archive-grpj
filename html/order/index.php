<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/captcha/require_captcha.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/protects/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/is_receive.php";
session_start();
unset($_SESSION["order"]["order_items"]);
?>
<?php
try {
    if (isset($_SESSION["temp_purchase"])) {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/purchases/purchase.php";
        $purchase_id = $_SESSION["temp_purchase"]["id"];
        $purchase = new Purchases();
        $purchase->get_from_temp_purchases_id($purchase_id);
        $purchase->delete();
        unset($_SESSION["temp_purchase"]);
    }
} catch (Throwable $th) {
    unset($_SESSION["temp_purchase"]);
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>モバイルオーダー</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <link rel="stylesheet" href="/common/regi.css">
</head>
<style>
    html,
    body {
        touch-action: pan-x pan-y;
        /* スクロールやスライドは有効にし、ズームは無効にする */
        -webkit-user-select: none;
        /* テキスト選択を無効にする */
    }

    body {
        /* パディング削減のため上書き */
        padding-top: 1rem;
    }
</style>

<body>
    <h1 class="text-center mb-3">モバイルオーダー</h1>
    <p>一つの商品につき10個まで注文ができます。それ以上お買い求めいただく場合は、店頭までお越しください。</p>
    <p>残り数が0になっている場合でも、店頭ではわずかに在庫が残っている場合がございます。</p>
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
                        <th>個数</th>
                        <th>価格</th>
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
                        <td><span id="total-count">0</span>個</td>
                        <td><span id="total-price">0</span>円</td>
                    </tr>
                </tbody>
            </table>
            <form action="./create/" method="post" class="text-center" id="form_cart">
                <div id="form"></div>
                <input id="submit_btn" type="submit" value="確認へ進む→" class="btn btn-success d-inline-block" disabled>
            </form>
        </div>
    </div>
    <div id="notifications" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    <script src="/common/regi.js"></script>
    <script src="/common/check_update_common.js"></script>
</body>

</html>