<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/protects/protect.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/is_receive.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/is_not_order.php";
?>
<?php
session_start();
setcookie("order", "", 0, "/");
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>受け取り完了</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <style>
        .custom-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .custom-image {
            max-width: 100px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container custom-container">
        <h1 class="display-4">受け取りが完了しました。</h1>
        <p class="lead">ご利用ありがとうございました。</p>
        <p><span id="back_second">30</span>秒後にトップページに戻ります。</p>
        <p><a href="/order/">戻る</a></p>
        <img src="/order/receive/ojigi_tenin_man.png" alt="お辞儀している人物" class="custom-image">
    </div>
</body>
<script>
    setInterval(() => {
        const second = document.getElementById("back_second");
        second.innerText -= 1;
        if (second.innerText <= 0) {
            location.href = "/order/";
        }
    }, 1000);
</script>

</html>