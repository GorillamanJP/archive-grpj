<?php
try {
    function stringToUrlB64($string) {
        $base64 = base64_encode($string);
        $urlSafeBase64 = str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            $base64
        );
        return $urlSafeBase64;
    }

    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/order_notifies/order_notify.php";

    $order_notify = new Order_Notify();

    $encoded_key = stringToUrlB64($order_notify->get_public_key());

    echo json_encode(["key" => $order_notify->get_public_key()]);
}catch(Throwable $t){
    http_response_code(500);
    echo json_encode(["Error" => $t->getMessage()]);
}