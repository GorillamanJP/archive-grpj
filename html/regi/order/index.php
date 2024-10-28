<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>モバイルオーダー</title>
</head>
<body>
    <h1>モバイルオーダーのレジ側トップ画面</h1>
    <h2>リンク生成ツール</h2>
    <input type="text" name="domain_name" id="domain_name" placeholder="ドメイン名">
    <p>モバイルオーダーのリンク:<pre><span id="url_domain"></span>/order/?magic_char=<?= file_get_contents("/var/www/.magic_char") ?></pre></p>
    <p>このリンクを共有することでモバイルオーダーにアクセスできるようになります。</p>
    <p>magic_charはコンテナが起動するたび書き換えられます。印刷などする場合には、コンテナを止めてしまわないようご注意ください。この問題について何かいい解決策をお持ちでしたら、開発者までご連絡ください。</p>
    <script>
        document.getElementById("domain_name").addEventListener("input", function(){
            document.getElementById("url_domain").innerText = document.getElementById("domain_name").value;
        });
    </script>
</body>
</html>