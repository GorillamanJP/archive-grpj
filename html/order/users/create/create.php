<?php
session_start();

$data = json_decode(file_get_contents("php://input"), true);

$fingerprint = $data["fingerprint"];

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/order_users/order_user.php";
try{
    $user = new Order_User();
    $user = $user->create($fingerprint);
    echo json_encode(["success" => true, "user_id" => $user->get_id()]);
    exit();
} catch(\Throwable $th){
    echo json_encode(["success" => false, "message" => $th->getMessage()]);
    exit();
}