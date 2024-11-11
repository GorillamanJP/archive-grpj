<?php
require_once $_SERVER['DOCUMENT_ROOT']."/regi/users/login_check.php";
?>
<?php
session_start();

$product_ids = $_SESSION["product_id"];
$quantities = $_SESSION["quantity"];
unset($_SESSION["product_id"]);
unset($_SESSION["quantity"]);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>支払い待機</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/common/list.css">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-warning text-center">
            <h2 class="alert-heading">ほかの端末が支払いを完了するのを待っています…</h2>
            <p id="message"><span id="second">5</span>秒後にリトライします…</p>
        </div>
        <form action="./" method="post" id="form" class="text-center">
            <?php foreach ($product_ids as $product_id) : ?>
                <input type="hidden" name="product_id[]" value="<?= $product_id ?>">
            <?php endforeach; ?>
            <?php foreach ($quantities as $quantity) : ?>
                <input type="hidden" name="quantity[]" value="<?= $quantity ?>">
            <?php endforeach; ?>
            <input type="submit" value="今すぐリトライ" class="btn btn-primary mb-3">
        </form>
        <div class="text-center">
            <a href="/regi/" class="btn btn-secondary">戻る</a>
        </div>
    </div>
    <script>
        // リトライ待機処理
        // 秒数は"span#second"の値がそのまま待ち時間になります
        setInterval(() => {
            const second = document.getElementById("second");
            second.innerText -= 1;
            if (second.innerText <= 0) {
                document.getElementById("message").innerHTML = "リトライしています…"
                document.getElementById("form").submit();
            }
        }, 1000);
    </script>
</body>
</html>