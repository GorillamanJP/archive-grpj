<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check_bool.php";
if (!login_check()) {
    http_response_code(403);
    exit();
}
?>
<?php
session_start();

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/purchases/purchase.php";
    $temp_purchase_id = isset($_SESSION["temp_purchase"]["id"]) ? $_SESSION["temp_purchase"]["id"] : null;
    if (isset($temp_purchase_id)) {
        $purchase = new Purchases();
        $purchase = $purchase->get_from_temp_purchases_id($temp_purchase_id);
        $purchase->get_temp_purchases()->extension();
        echo "keepalive success";
    }
} catch (Throwable $th) {
    echo $th->getMessage();
}