<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/protects/protect.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/users/user_check.php";
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
    <h2>CAPTCHAテスト</h2>
    <form method="post" action="verify_captcha.php">
        <img src="generate_captcha.php" alt="CAPTCHA">
        <input type="text" name="captcha" required>
        <button type="submit">Submit</button>
    </form>

</body>

</html>