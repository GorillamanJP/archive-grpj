<?php
session_start();
?>
<?php
$before_url = $_SESSION["order"]["captcha"]["before"]["url"];
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAPTCHA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</head>

<body>
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
    <h1>暗号を解いてください。</h1>
    <p>ヒント: ひらがな+半角数字、8文字</p>
    <form method="post" action="../captcha/verify_captcha.php">
        <img src="../captcha/generate_captcha.php" alt="CAPTCHA"><br>
        <input type="text" name="captcha" minlength="8" maxlength="8" required placeholder="画像に書かれた文字を入力してください">
        <button type="submit">認証</button>
    </form>
    <a href="<?= $before_url ?>">戻る</a>
</body>

</html>