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
                    <h4>【登録】</h4>
                    <p>①　ユーザー管理ページのユーザー登録ボタンを押します。</p>
                    <p>②　ユーザー名、パスワードを入力して、登録ボタンを押すことで登録が完了します。</p>
                    <h4>【ログイン】</h4>
                    <p>・ログイン画面が表示されたら、ユーザー名とパスワードを入力してログインします。</p>
                </div>
                <div class="manual-section" id="regi">
                    <h2>レジ画面</h2>
                    <h4>【レジ画面の操作】</h4>
                    <p>①　会計画面から会計処理を行います。登録されている商品から選択し、数量を入力して、会計ボタンを押します。</p>
                    <p>②　支払い画面に進むので、お預かりした金額を入力し、購入確定ボタンを押します。</p>
                    <p>③　確認画面で本当に合っているか確認し、合っていれば購入確定ボタンを、間違っているのならキャンセルボタンで再度入力します。</p>
                    <p>④　お釣りがあれば確認画面に進むので、お釣りを渡してから確認ボタンを押します。</p>
                    <p>⑤　確認ボタンを押すと、最初のレジ画面に戻り、緑の通知が出ていれば会計が完了しています。</p>
                </div>
                <div class="manual-section" id="product">
                    <h2>商品管理</h2>
                    <h4>【商品登録】</h4>
                    <p>・商品画像、商品名、価格、在庫数を入力し、登録ボタンを押します。</p>
                    <h4>【商品情報の更新】</h4>
                    <p>①　更新したい商品の行の更新ボタンを押します。</p>
                    <p>②　商品画像、商品名、価格を入力し、更新ボタンを押します。</p>
                    <h4>【在庫数の追加】</h4>
                    <p>①　在庫を追加したい商品の行の入荷ボタンを押します。</p>
                    <p>②　追加数を入力し、更新ボタンを押します。</p>
                    <p>③　更新の確認画面に進むので、合っているかを確認し、更新ボタンを押すことで追加が完了します。(違っていればキャンセルボタンを押して、再度入力を行ってください。)</p>
                </div>
                <div class="manual-section" id="sales">
                    <h2>会計一覧</h2>
                    <p>・注文履歴画面から過去の注文を確認できます。</p>
                    <p>・注文番号、注文日時、注文内容、状態を確認できます。</p>
                </div>
            </div>
            <div class="manual-section" id="order">
                <h2>モバイルオーダー</h2>
                <h4>【レジ側】</h4>
                <p>①　モバイルオーダーで注文が入ると、</p>
                <p>②　あ</p>
                <p>③　い</p>
            </div>
        </div>
        <div class="manual-section" id="notify">
            <h2>通知の確認</h2>
            <p>・通知画面からシステムからの通知を確認できます。</p>
            <p>・新しい通知がある場合、通知アイコンにバッジが表示されます。</p>
        </div>
    </div>
</body>

</html>