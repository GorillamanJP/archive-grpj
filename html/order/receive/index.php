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
    <title>モバイルオーダー/受け取り完了</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
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
        <div class="alert alert-success text-center mx-auto mt-3" role="alert">
            <h3>利用者アンケート実施中！</h3>
            <p>モバイルオーダーを使ってみての評価をお願い致します！</p>
            <p><a href="">アンケートを開く（Googleフォーム）</a></p>
        </div>
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