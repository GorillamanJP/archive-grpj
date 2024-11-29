<?php
session_start();
if(!isset($_SESSION["order"]["warning"]) || $_SESSION["order"]["warning"] === ""){
    session_write_close();
    header("Location: /order/");
    exit();
}
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
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
</head>
<body>
    <h1>エラー</h1>
    <h2><?= $message ?></h2>
    <p><?= $details ?></p>
</body>
</html>