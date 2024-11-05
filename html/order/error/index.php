<?php
session_start();
$message = $_SESSION["order"]["warning"]["message"];
$details = $_SESSION["order"]["warning"]["message_details"];

unset($_SESSION["order"]["warning"]);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>エラー</title>
</head>
<body>
    <h1>エラー</h1>
    <h2><?= $message ?></h2>
    <p><?= $message_details ?></p>
</body>
</html>