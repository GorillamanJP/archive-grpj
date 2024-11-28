<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";

session_start();

if (!isset($_POST["order_id"]) || $_POST["order_id"] === "") {
    redirect_with_error("../list/", "注文番号が指定されていません。", "", "warning");
}

$id = htmlspecialchars($_POST["order_id"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";

try {
    $order = new Order();
    $order = $order->get_order_order()->get_from_id($id);
    $order->call();

    redirect_with_error("../list/", "注文番号 {$id} 番を呼び出しました！", "", "success");
} catch (Throwable $e) {
    redirect_with_error("../list/", "エラーが発生しました。", $e->getMessage(), "danger");
}