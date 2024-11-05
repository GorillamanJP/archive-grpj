<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/protects/protect.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT']."/order/receive/is_receive.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT']."/order/is_not_order.php";
?>
<?php
session_start();
setcookie("order", "", 0, "/");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>受け取り完了</title>
</head>
<body>
    <h1>受け取りが完了しました。</h1>
    <p>ご利用ありがとうございました。</p>
</body>
</html>