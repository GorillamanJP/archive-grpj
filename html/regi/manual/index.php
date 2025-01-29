<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レジ/マニュアル</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <link rel="stylesheet" href="/common/manual.css">
</head>

<body>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/navbar.php"; ?>
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
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
                        <li><a href="#credit">クレジット</a></li>
                    </ul>
                </div>
                <div class="manual-section" id="user">
                    <h2>ユーザー登録／ログイン</h2>
                    <h4>【登録】</h4>
                    <p>①　ユーザー管理ページのユーザー登録ボタンを押します。</p>
                    <p>②　ユーザー名、パスワードを入力して、登録ボタンを押すことで登録が完了します。</p>
                    <h4>【ログイン】</h4>
                    <p>・ログイン画面が表示されたら、ユーザー名とパスワードを入力してログインします。</p>
                    <p>・一定時間(1時間)操作がなければ、自動でログアウトされます。</p>
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
                <p>・モバイルオーダーで注文が入ると、受け取り待ち一覧画面に注文内容が表示されます。</p>
                <h3>【モバイルオーダーの公開】</h3>
                <p>・「モバイルオーダーを公開する」を押すと、モバイルオーダーのリンクが表示されます。</p>
                <p>・リンクをＱＲコードにして、お客様に読み取ってもらったり、リンクからページへ飛んでもらうことでモバイルオーダーでの注文が出来ます。</p>
                <h3>【ボタン説明】</h3>
                <h4>　詳細</h4>
                <p>・注文内容の詳細を確認できます。</p>
                <h4>　受取</h4>
                <p>・受取ボタンを押すことで、お支払い画面へ進みます。(お支払い画面の操作説明は、レジ画面での操作と同様です。)</p>
                <h4>　呼出</h4>
                <p>・呼出ボタンを押すことで、モバイルオーダーで注文待ちのお客様にお知らせすることが出来ます。</p>
                <p>・呼び出し中にもう一度呼出ボタンを押すと、呼び出しをキャンセルできます。</p>
                <h4>　取消</h4>
                <p>・取消ボタンを押すことで、モバイルオーダーの注文をレジ側からキャンセルすることが出来ます。</p>
                <h3>【履歴一覧】</h3>
                <p>・履歴一覧画面では、会計済やキャンセル済を含めた全てのモバイルオーダーの注文履歴を確認できます。</p>
                <p>・各履歴を押すことで、詳細な履歴が確認できます。</p>
                <h3>【呼び出し一覧】</h3>
                <p>・呼び出し一覧画面は、モバイルオーダーを利用されるお客様に向けた画面となります。</p>
                <p>・モバイルオーダーで注文されたお客様の番号が表示され、こちらで呼出ボタンを押すと、呼び出し中の方に番号が表示されるようになります。</p>
                <p>・この画面を大画面でお客様に見せることで、モバイルオーダーの注文や呼び出しの状況が確認できるようになります。</p>
                <p>（例：マックの呼び出し番号を表示する画面）</p>
            </div>
        </div>
        <div class="manual-section" id="notify">
            <h2>通知の確認</h2>
            <p>・通知画面からシステムからの通知を確認できます。</p>
            <p>・新しい通知がある場合、通知アイコンにバッジが表示されます。</p>
        </div>
        <div class="manual-section" id="credit">
            <h2>クレジット</h2>
            <p>開発：穴吹ビジネス専門学校　情報システム学科　チーム裏林</p>
            <p>イラスト：<i class="bi bi-twitter-x"></i><a href="https://x.com/mipo_00516">みぽ（@mipo_00516）</a>様</p>
            <p>効果音：<a href="https://soundeffect-lab.info/">効果音ラボ</a>様</p>
            <p>&copy; 2024-25　穴吹ビジネス専門学校　情報システム学科　チーム裏林</p>
        </div>
    </div>
    <div id="notifications" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    <button id="back-to-top" class="btn btn-primary" title="トップに戻る"><i class="fa-solid fa-angle-up"></i></button>
    <script src="/regi/notify/check_notify.js"></script>
    <script>
        // スクロールイベントを監視してボタンの表示/非表示を切り替える
        window.onscroll = function() {
            const backToTopButton = document.getElementById("back-to-top");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                backToTopButton.style.display = "block";
            } else {
                backToTopButton.style.display = "none";
            }
        };

        // ボタンクリックでトップに戻る
        document.getElementById("back-to-top").onclick = function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };
    </script>
</body>

</html>