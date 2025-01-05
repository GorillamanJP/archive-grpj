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
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/order_notifies/order_notify.php";

try {
    $order = new Order();
    $order = $order->get_order_order()->get_from_id($id);
    $order->call();

    $message = "";
    if ($order->get_is_call()) {
        $message = "注文番号 {$id} 番を呼び出しました！";

        $order_notify = new Order_Notify();
        try {
            $order_notify = $order_notify->get_from_order_id($order->get_id());
            $order_notify->call();

            $message .= "（プッシュ通知送信済み）";
        } catch (Exception $e) {
            if ($e->getCode() != 0) {
                throw new Exception($e->getMessage(), $e->getCode(), $e);
            }
        }
    } else {
        $message = "注文番号 {$id} 番の呼び出しをキャンセルしました！";
    }

    redirect_with_error("../list/", $message, "", "success");
} catch (Throwable $e) {
    redirect_with_error("../list/", "エラーが発生しました。", $e->getMessage(), "danger");
}