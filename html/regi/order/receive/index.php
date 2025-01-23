<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";

session_start();

if (!isset($_POST["order_id"]) || $_POST["order_id"] === "") {
    redirect_with_error("../list/", "指定した注文はありません。", "", "danger");
}

$order_id = htmlspecialchars($_POST["order_id"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";
try {
    $order = new Order();
    $order = $order->get_from_order_id($order_id);
    $details = $order->get_order_details();
    ?>
    <form action="/regi/sales/create/" method="post" id="form">
        <?php foreach ($details as $detail): ?>
            <input type="hidden" name="product_id[]" id="product_id[]" value="<?= $detail->get_item_id() ?>">
            <input type="hidden" name="quantity[]" id="quantity[]" value="<?= $detail->get_quantity() ?>">
        <?php endforeach; ?>
    </form>
    <?php
    $_SESSION["regi"]["order"]["id"] = $order->get_order_order()->get_id();
    session_write_close();
    ?>
    <script>
        document.getElementById("form").submit();
    </script>
    <?php
    exit();
} catch (\Throwable $e) {
    $_SESSION["message"] = "エラーが発生しました。";
    $_SESSION["message_details"] = $e->getMessage();
    $_SESSION["message_type"] = "danger";
}
session_write_close();
?>
<script>
    location.href = "../list/";
</script>
<?php
exit();