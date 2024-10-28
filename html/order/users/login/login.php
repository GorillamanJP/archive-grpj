<?php
session_start();

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $data["user_id"];
$fingerprint = $data["fingerprint"];

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/order_users/order_user.php";
try {
    $user = new Order_User();
    $user = $user->get_from_id($user_id);
    $user->verify($fingerprint);
    // 例外を踏まなければ無事ログイン成功
    echo json_encode(["success" => true]);
    exit();
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "message" => $th->getMessage()]);
    exit();
}