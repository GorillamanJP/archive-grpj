<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/captcha/require_captcha.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/protects/protect.php";
?>
<?php
session_start();
unset($_SESSION["order"]["order_items"]);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>モバイルオーダー</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</head>

<body>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
    <h1>モバイルオーダートップページ</h1>
    <h2>仮の処理</h2>
    <p>いずれレジと同じ処理にする</p>
    <form action="./create/" method="post">
        <button type="button" id="new_cart">カート追加ボタン（仮）</button>
        <div id="cart">
        </div>
        <button type="submit">注文確認→</button>
    </form>
    <!-- ここに商品一覧をもってくる -->
    <h2>注文内容</h2>
    <a href="./create/">注文確認（仮）</a>
</body>
<script>
    document.getElementById("new_cart").addEventListener("click", () => {
        document.getElementById("cart").innerHTML += '<div><input type="number" name="product_id[]" id="product_id[]" placeholder="商品番号"><input type="number" name="quantity[]" id="quantity[]" placeholder="個数"></div>';
    });
</script>

</html>