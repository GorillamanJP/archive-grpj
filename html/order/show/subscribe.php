<?php
session_start();

if (!isset($_COOKIE["order"]) || !isset($_POST["endpoint"]) || !isset($_POST["userPublicKey"]) || !isset($_POST["userAuthToken"])) {
    http_response_code(400);
    var_dump($_COOKIE, $_POST);
    exit();
}

try {
    $endpoint = htmlspecialchars($_POST["endpoint"]);
    $user_public_key = htmlspecialchars($_POST["userPublicKey"]);
    $user_auth_token = htmlspecialchars($_POST["userAuthToken"]);
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/decrypt_id.php";
    $order_id = decrypt_id($_COOKIE["order"]);

    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/order_notifies/order_notify.php";
    $order_notify = new Order_Notify();

    // ここで注文番号を検索し、すでにあるなら何もしない（例外にならない）
    // 注文番号がなかったら例外が発生するので、例外の種類を見て処理
    // 0なら被りありのエラーなので上書きさせる、それ以外は知らない例外なので例外を再度送る
    try {
        $order_notify->get_from_order_id($order_id);
    } catch (Exception $e) {
        if ($e->getCode() == 0) {
            $order_notify = $order_notify->create($order_id, $endpoint, $user_public_key, $user_auth_token);
        } else {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
    http_response_code(200);
    exit();
} catch (Throwable $th) {
    http_response_code(500);
    echo json_encode(["Error" => $th->getMessage()]);
}