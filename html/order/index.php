<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/protects/protect.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/users/user_check.php";
?>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>モバイルオーダー</title>
</head>

<body>
    <h1>モバイルオーダートップページ</h1>
    <!-- ここに商品一覧をもってくる -->
    <h2>注文内容</h2>
    <h3>暗号を解いて注文を送信してください。</h3>
    <p>ヒント: ひらがな+半角数字、8文字</p>
    <form method="post" action="./captcha/verify_captcha.php">
        <img src="./captcha/generate_captcha.php" alt="CAPTCHA"><br>
        <input type="text" name="captcha" required>
        <button type="submit">認証</button>
    </form>
    <a href="./create/">注文確認（仮）</a>
</body>

</html>