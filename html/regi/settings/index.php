<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
session_start();

$magic_char = getenv("PASS_PHRASE");
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>設定</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT']."/common/header.php"; ?>
</head>
<body>
    <h1>設定</h1>
    <div>
        <h2>合言葉</h2>
        <p>モバイルオーダーのリンクを安全にするために、暗号化したものが使われます。</p>
        <form action="./set_magic_char.php" method="post">
            <input type="text" name="magic_char" id="magic_char" value="<?= $magic_char ?>">
        </form>
    </div>
</body>
</html>