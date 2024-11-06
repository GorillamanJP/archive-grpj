<?php
session_start();
$before_url = isset($_SESSION["order"]["captcha"]["before"]["url"]) && $_SESSION["order"]["captcha"]["before"]["url"] !== "" ? $_SESSION["order"]["captcha"]["before"]["url"] : "/order/";
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAPTCHA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <style>
        .captcha-container {
            max-width: 400px;
            margin: auto;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
    </style>
</head>

<body class="bg-light">
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
    <div class="container mt-5">
        <div class="captcha-container bg-white shadow-sm">
            <h1 class="h4 text-center">暗号を解いてください。</h1>
            <p class="text-center">ヒント: ひらがな+半角数字、8文字</p>
            <form method="post" action="../captcha/verify_captcha.php">
                <div class="mb-3 text-center">
                    <img src="../captcha/generate_captcha.php" alt="CAPTCHA" class="img-fluid mb-2"><br>
                </div>
                <div class="mb-3">
                    <input type="text" name="captcha" minlength="8" maxlength="8" class="form-control" required
                        placeholder="画像に書かれた文字を入力してください">
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">認証</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <a href="<?= $before_url ?>" class="btn btn-secondary">戻る</a>
            </div>
        </div>
    </div>
</body>

</html>