<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/decrypt_id.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";
session_start();

// 注文番号があるかチェック
if (isset($_COOKIE["order"]) && $_COOKIE["order"] !== "") {
    try {
        $order = new Order();
        $id = decrypt_id($_COOKIE["order"]);
        $order->get_from_order_id($id);
        if ($order->get_order_order()->get_is_received() == false) {
            session_write_close();
            header("Location: /order/show/");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION["order"]["warning"]["message"] = "注文番号が読み取れませんでした。";
        $_SESSION["order"]["warning"]["message_details"] = "クッキーに保存した注文番号が改ざんされた可能性があります。恐れ入りますが、店頭スタッフまでお尋ねください。";
        session_write_close();
        header("Location: /order/error/");
        exit();
    } catch (\Throwable $th) {
        $_SESSION["message"] = "予期しないエラーが発生しました。";
        $_SESSION["message_details"] = $th->getMessage();
        $_SESSION["message_type"] = "danger";
        session_write_close();
        header("Location: /order/error/");
        exit();
    }
}

// 入ってなかったら素通り
session_write_close();