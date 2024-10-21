<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
session_start();

$product_ids = $_SESSION["product_id"];
$quantities = $_SESSION["quantity"];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>支払い待機</title>
</head>
<body>
    <h2>ほかの端末が支払いを完了するのを待っています…</h2>
    <p><span id="second">5</span>秒後にリトライします…</p>
    <form action="./" method="post" id="form">
        <?php foreach ($product_ids as $product_id) : ?>
            <input type="hidden" name="product_id[]" value="<?= $product_id ?>">
        <?php endforeach; ?>
        <?php foreach ($quantities as $quantity) : ?>
            <input type="hidden" name="quantity[]" value="<?= $quantity ?>">
        <?php endforeach; ?>
        <input type="submit" value="今すぐリトライ">
    </form>
    <a href="/regi/">戻る</a>
</body>
<script>
    // リトライ待機処理
    // 秒数は"span#second"の値がそのまま待ち時間になります
    setInterval(() => {
        const second = document.getElementById("second");
        second.innerText -= 1;
        if(second.innerText <= 0){
            document.getElementById("form").submit();
        }
    }, 1000);
</script>
</html>