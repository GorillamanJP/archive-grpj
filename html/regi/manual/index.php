<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レジ機能ユーザーマニュアル</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <link rel="stylesheet" href="/common/manual.css">
</head>

<body>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/navbar.php"; ?>
    <div class="container mt-4">
        <h1 class="text-center mb-4">レジ機能ユーザーマニュアル</h1>
        <div class="manual-section">
            <h2>目次</h2>
            <ul>
                <li><a href="#section1">ログイン方法</a></li>
                <li><a href="#section2">商品登録</a></li>
                <li><a href="#section3">注文履歴の確認</a></li>
                <li><a href="#section4">会計処理</a></li>
                <li><a href="#section5">通知の確認</a></li>
            </ul>
        </div>
        <div class="manual-section" id="section1">
            <h2>ログイン方法</h2>
            <p>ログイン画面からユーザー名とパスワードを入力してログインします。</p>
        </div>
        <div class="manual-section" id="section2">
            <h2>商品登録</h2>
            <p>商品管理画面から新しい商品を登録します。</p>
        </div>
        <div class="manual-section" id="section3">
            <h2>注文履歴の確認</h2>
            <p>注文履歴画面から過去の注文を確認できます。</p>
        </div>
        <div class="manual-section" id="section4">
            <h2>会計処理</h2>
            <p>会計画面から会計処理を行います。</p>
        </div>
        <div class="manual-section" id="section5">
            <h2>通知の確認</h2>
            <p>通知画面からシステムからの通知を確認できます。</p>
        </div>
    </div>
</body>

</html></h2>