<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/captcha/require_captcha.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/protects/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/receive/is_receive.php";
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
    <style>
        .cart-container {
            max-width: 600px;
            margin: auto;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
    </style>
</head>

<body class="bg-light">
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
    <div class="container mt-5">
        <div class="cart-container bg-white shadow-sm">
            <h1 class="h4 text-center">モバイルオーダートップページ</h1>
            <p class="text-center">仮の処理: いずれレジと同じ処理にする</p>
            <form action="./create/" method="post">
                <div class="mb-3 text-center">
                    <button type="button" id="new_cart" class="btn btn-outline-primary mb-2">カート追加ボタン（仮）</button>
                </div>
                <div id="cart"></div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-success">注文確認→</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById("new_cart").addEventListener("click", () => {
            document.getElementById("cart").innerHTML += '<div class="mb-2"><input type="number" name="product_id[]" id="product_id[]" class="form-control mb-1" placeholder="商品番号"><input type="number" name="quantity[]" id="quantity[]" class="form-control" placeholder="個数"></div>';
        });
    </script>
</body>

</html>