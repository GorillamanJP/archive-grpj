<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/captcha/require_captcha.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/is_receive.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_alert_with_form.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/verify_int_value.php";

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
    redirect_with_error("/order/", $message, "", "warning");
}

$product_ids = $_SESSION["order"]["data"]["product_id"];
$product_names = $_SESSION["order"]["data"]["product_name"];
$product_prices = $_SESSION["order"]["data"]["product_price"];
$quantities = $_SESSION["order"]["data"]["quantity"];
$subtotals = $_SESSION["order"]["data"]["subtotal"];

$total_amount = $_SESSION["order"]["data"]["total_amount"];
$total_price = $_SESSION["order"]["data"]["total_price"];

if (verify_int_value($total_amount, $total_price) == false) {
    redirect_with_error("/order/", "数値エラー", "入力内容のうちいずれかが小数になっているか、あまりにも大きい数になっている可能性があります。", "danger");
}

$checksum_txt = "";
foreach ($product_names as $name) {
    $checksum_txt .= $name;
}
foreach ($product_prices as $price) {
    $checksum_txt .= $price;
}
foreach ($quantities as $quantity) {
    $checksum_txt .= $quantity;
}
foreach ($subtotals as $subtotal) {
    $checksum_txt .= $subtotal;
}
$checksum_txt .= $total_amount;
$checksum_txt .= $total_price;

$checksum = hash("sha256", $checksum_txt);

if (!isset($_POST["checksum"]) || $_POST["checksum"] != $checksum) {
    redirect_with_error("/order/", "チェックサムエラーです。", "入力内容が改ざんされた可能性があります。", "danger");
}

unset($_SESSION["order"]["data"]);

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";
    $order = new Order();
    $order->create($product_ids, $product_names, $product_prices, $quantities, $subtotals, $total_amount, $total_price);
    $id = $order->get_order_order()->get_id();

    // 1か月後
    $expire = time() + (30 * 24 * 60 * 60);

    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/encrypt_id.php";
    $enc_id = encrypt_id($id);
    setcookie("order", $enc_id, $expire, "/");
    redirect_with_error("../show/", "注文番号 {$id}番 で処理を完了しました。", "", "success");
} catch (\Throwable $e) {
    redirect_with_error("/order/", "エラーが発生しました。", $e->getMessage(), "danger");
}