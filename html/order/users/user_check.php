<?php
session_start();

// セッションにユーザー情報が書き込まれていない場合はログインに飛ばす
if (!isset($_SESSION["order"]["user_id"])) {
    header("Location: ./login.php");
    exit();
}

// ユーザーが正しいかチェック
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/order_users/order_user.php";
try {
    $id = $_SESSION["order"]["user_id"];

    $order_user = new Order_User();
    $order_user->get_from_id($id);

    exit();
} catch (\Throwable $th) {
    session_write_close();
    switch ($th->getCode()) {
        case 0:
            header("Location: ./create.php");
            break;

        default:
            header("Location: /order/");
            break;
    }
    exit();
}