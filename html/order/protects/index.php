<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>モバイルオーダー/アクセスエラー</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <style>
        .custom-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container custom-container">
        <div class="alert alert-danger" role="alert">
            <h1 class="display-4">アクセスエラー</h1>
            <p>このページを一度閉じて、モバイルオーダー用のQRコードを読み取りなおしてください。</p>
        </div>
    </div>
</body>
</html>
