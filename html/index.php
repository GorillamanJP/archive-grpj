<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<style>
    .alert-info {
        text-align: center;
        background-color: #d1ecf1;/* 背景色 */
        border-color: #bee5eb;/* ボーダー色 */
        color: #0c5460;/* 文字色 */
        border-radius: 8px;/* 丸みを持たせた角 */
        max-width: 600px;/* 最大横幅を指定 */
        width: 90%;/* 横幅を親要素の90%に設定 */
        margin: 0 auto;/* 左右自動マージンで中央揃え */
        font-weight: bold;/* リンク文字を太字に */
        padding: 5px;/* 上下10px、左右25pxの余白 */
        margin-top: 20px;/* 上部の余白 */
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>楽ちんぽん！レジシステム</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <h1 class="text-center mt-3">楽ちんぽん！レジシステム</h1>
    <div class="alert-info">
        <h2>レジ</h2>
        <p>レジ利用者は<a href="/regi/">こちら</a>から</p>
    </div>
    <div class="alert-info">
        <h2>モバイルオーダー</h2>
        <p>ご利用の方は、ポスターにあるQRコードを読み込んでご利用ください。</p>
    </div>
</body>

</html>