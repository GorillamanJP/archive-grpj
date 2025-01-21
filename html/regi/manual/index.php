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
        <div class="row">
            <div class="col-12">
                <div class="manual-section">
                    <h2>目次</h2>
                    <ul class="list-unstyled">
                        <li><a href="#user">ユーザー登録／ログイン</a></li>
                        <li><a href="#regi">レジ画面</a></li>
                        <li><a href="#product">商品管理</a></li>
                        <li><a href="#sales">会計一覧</a></li>
                        <li><a href="#order">モバイルオーダー</a></li>
                        <li><a href="#notify">通知の確認</a></li>
                    </ul>
                </div>
                <div class="manual-section" id="user">
                    <h2>ユーザー登録／ログイン</h2>
                    <p>・ログイン画面からユーザー名とパスワードを入力してログインします。</p>
                    <p>・ログインに成功すると、レジ画面に移動します。</p>
                </div>
                <div class="manual-section" id="regi">
                    <h2>レジ画面</h2>
                    <p>会計画面から会計処理を行います。商品を選択し、数量を入力して、会計ボタンを押します。支払い金額を入力し、確認ボタンを押します。</p>
                </div>
                <div class="manual-section" id="product">
                    <h2>商品管理</h2>
                    <p>商品管理画面から新しい商品を登録します。商品名、価格、在庫数、商品画像を入力し、登録ボタンを押します。</p>
                </div>
                <div class="manual-section" id="sales">
                    <h2>会計一覧</h2>
                    <p>注文履歴画面から過去の注文を確認できます。注文番号、注文日時、注文内容、状態を確認できます。</p>
                </div>
            </div>
            <div class="manual-section" id="order">
                <h2>モバイルオーダー</h2>
                <p>注文を受けれます。</p>
            </div>
        </div>
        <div class="manual-section" id="notify">

            <h2>通知の確認</h2>
            <p>通知画面からシステムからの通知を確認できます。新しい通知がある場合、通知アイコンにバッジが表示されます。</p>
        </div>
    </div>
</body>

</html>