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
    <form action="./" method="post" id="form">
        <?php foreach ($product_ids as $product_id) : ?>
            <input type="hidden" name="product_id[]" value="<?= $product_id ?>">
        <?php endforeach; ?>
        <?php foreach ($quantities as $quantity) : ?>
            <input type="hidden" name="quantity[]" value="<?= $quantity ?>">
        <?php endforeach; ?>
    </form>
    <a href="/regi/">戻る</a>
</body>
<script>
    setTimeout(function(){
        document.getElementById("form").submit();
    }, 10000);
</script>
</html>