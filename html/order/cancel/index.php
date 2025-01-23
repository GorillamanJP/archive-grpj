<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/protects/protect.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/is_not_order.php";
?>
<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/decrypt_id.php";

try {
    $order_id = decrypt_id(htmlspecialchars($_COOKIE["order"]));
    $order = new Order();
    $order = $order->get_from_order_id($order_id);

    if ($order->get_order_order()->get_is_cancel() == false) {
        session_write_close();
        header("Location: ../show/");
        exit();
    }

    setcookie("order", "", 0, "/");
} catch (Exception $e) {
    $_SESSION["order"]["warning"]["message"] = "注文番号が読み取れませんでした。";
    $_SESSION["order"]["warning"]["message_details"] = "クッキーに保存した注文番号が改ざんされた可能性があります。恐れ入りますが、店頭スタッフまでお尋ねください。";
    session_write_close();
    header("Location: /order/error/");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>モバイルオーダー/キャンセル</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
</head>
<style>
    .custom-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
        text-align: center;
    }

    .custom-image {
        max-width: 100px;
        margin-top: 20px;
    }
</style>

<body>
    <div class="container custom-container">
        <h1 class="display-4">注文はキャンセルされました。</h1>
        <p><span id="back_second">30</span>秒後にトップページに戻ります。</p>
        <p><a href="/order/">戻る</a></p>
        <img src="/order/receive/ojigi_tenin_man.png" alt="お辞儀している人物" class="custom-image">
    </div>
</body>

<script>
    setInterval(() => {
        const second = document.getElementById("back_second");
        second.innerText -= 1;
        if (second.innerText <= 0) {
            location.href = "/order/";
        }
    }, 1000);
</script>

</html>