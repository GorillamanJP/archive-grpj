<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>モバイルオーダー@レジ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        h1 {
            color: #0056b3;
            font-size: 2rem;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
            font-size: 1.5rem;
            margin-top: 30px;
        }

        .link-container {
            margin-top: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .link-container pre {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 4px;
            word-wrap: break-word;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }

        .copy-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            margin-top: 10px;
            cursor: pointer;
        }

        .copy-button:hover {
            background-color: #218838;
        }

        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/navbar.php"; ?>
    <h1>モバイルオーダーのレジ側トップ画面</h1>

    <!-- URL生成セクション -->
    <div class="link-container">
        <h2>モバイルオーダーのリンク</h2>
        <pre><span id="url_domain"></span>/order/?magic_char=<?= hash("SHA3-512", getenv("PASS_PHRASE")); ?></pre>
        <button class="copy-button" onclick="copyToClipboard()">リンクをコピー</button>
    </div>

    <!-- 情報提供セクション -->
    <div class="alert-info">
        <p>このリンクをコピーして、モバイルオーダーを開始することができます。モバイル端末でこのURLにアクセスし、注文を受け付けてください。</p>
    </div>

    <div class="text-center my-3">
    <a href="/regi/order/list/" class="btn btn-outline-primary btn-lg p-2 mx-1">注文一覧</a>
    </div>

    <script>
        // URLのホスト名を動的に挿入
        document.getElementById("url_domain").innerText = window.location.hostname;

        // コピー用の関数
        function copyToClipboard() {
            const urlText = document.querySelector("#url_domain").innerText + "/order/?magic_char=<?= hash("SHA3-512", getenv("PASS_PHRASE")); ?>";
            navigator.clipboard.writeText(urlText)
                .then(() => {
                    alert("リンクがコピーされました！");
                })
                .catch((err) => {
                    alert("コピーに失敗しました。");
                });
        }
    </script>
</body>

</html>