<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php"; ?>
<?php session_start(); ?>
<?php
$user = new User();
$user = $user->get_from_id($_SESSION["login"]["user_id"]);
?>
<?php
unset($_SESSION["regi"]["data"]);
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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>レジ</title>
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
</style>

<body>
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/navbar.php"; ?>
    <!-- 残りのページ内容 -->
    <h1 class="text-center my-3">レジ</h1>
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
            <form action="./sales/create/" method="post" class="text-center" id="form_cart">
                <div id="form"></div>
                <input id="submit_btn" type="submit" value="支払いへ進む→" class="btn btn-success d-inline-block" disabled>
            </form>
        </div>
    </div>
    <div id="notifications" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    <script src="/common/regi.js"></script>
    <script src="/common/check_update_common.js"></script>
    <script src="/regi/notify/check_notify.js"></script>
</body>

</html>