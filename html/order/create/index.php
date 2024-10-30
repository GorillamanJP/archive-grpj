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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注文確認</title>
</head>
<body>
    <h1>注文確認</h1>
    <h2>注文内容は以下の通りでよろしいですか？</h2>
    <a href="create.php">注文確定（仮）</a>
</body>
</html>