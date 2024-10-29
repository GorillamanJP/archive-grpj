<?php
require_once $_SERVER['DOCUMENT_ROOT']."/regi/users/login_check.php";
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
</head>
<body>
    <h1>モバイルオーダーのレジ側トップ画面</h1>
    <h2>リンク生成ツール</h2>
    <p>モバイルオーダーのリンク:<pre><span id="url_domain"></span>/order/?magic_char=<?= hash("SHA3-512", getenv("PASS_PHRASE")); ?></pre></p>
    <p>このリンクを共有することでモバイルオーダーにアクセスできるようになります。</p>
    <script>
        document.getElementById("url_domain").innerText = window.location.hostname;
    </script>
</body>
</html>