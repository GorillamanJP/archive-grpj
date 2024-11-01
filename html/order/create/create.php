<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/captcha/require_captcha.php";
?>
<?php
session_start();

$ok = true;
$message = "";

if (!isset($_SESSION["order"]["data"]["product_id"]) || $_SESSION["order"]["data"]["product_id"] === "") {
    $message .= "「商品ID」";
    $ok = false;
}
if (!isset($_SESSION["order"]["data"]["product_name"]) || $_SESSION["order"]["data"]["product_name"] === "") {
    $message .= "「商品名」";
    $ok = false;
}
if (!isset($_SESSION["order"]["data"]["product_price"]) || $_SESSION["order"]["data"]["product_price"] === "") {
    $message .= "「価格」";
    $ok = false;
}
if (!isset($_SESSION["order"]["data"]["quantity"]) || $_SESSION["order"]["data"]["quantity"] === "") {
    $message .= "「購入数」";
    $ok = false;
}
if (!isset($_SESSION["order"]["data"]["subtotal"]) || $_SESSION["order"]["data"]["subtotal"] === "") {
    $message .= "「小計」";
    $ok = false;
}
if (!isset($_SESSION["order"]["data"]["total_amount"]) || $_SESSION["order"]["data"]["total_amount"] === "") {
    $message .= "「合計購入数」";
    $ok = false;
}
if (!isset($_SESSION["order"]["data"]["total_price"]) || $_SESSION["order"]["data"]["total_price"] === "") {
    $message .= "「合計金額」";
    $ok = false;
}

if (!$ok) {
    $message .= "の入力項目が空になっています。";
    $_SESSION["message"] = $message;
    $_SESSION["message_type"] = "danger";
    session_write_close();
    header("Location: /order/");
    exit();
}

$product_names = $_SESSION["order"]["data"]["product_name"];
$product_prices = $_SESSION["order"]["data"]["product_price"];
$quantities = $_SESSION["order"]["data"]["quantity"];
$subtotals = $_SESSION["order"]["data"]["subtotal"];

$total_amount = $_SESSION["order"]["data"]["total_amount"];
$total_price = $_SESSION["order"]["data"]["total_price"];

unset($_SESSION["order"]["data"]);

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";
    $order = new Order();
    $order->create($product_names, $product_prices, $quantities, $subtotals, $total_amount, $total_price);
    $id = $order->get_order_order()->get_id();

    // 1か月後
    $expire = time() + (30 * 24 * 60 * 60);

    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/encrypt_id.php";
    $enc_id = encrypt_id($id);
    setcookie("order", $enc_id, $expire, "/");
    $_SESSION["message"] = "注文番号 {$id}番 で処理を完了しました。";
    $_SESSION["message_type"] = "success";
} catch (\Throwable $e) {
    $_SESSION["message"] = "エラーが発生しました。";
    $_SESSION["message_details"] = $e->getMessage();
    $_SESSION["message_type"] = "danger";
    session_write_close();
    header("Location: /order/");
    exit();
}

session_write_close();
header("Location: ../show/");
exit();