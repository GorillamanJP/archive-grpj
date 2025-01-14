<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";

use Minishlink\WebPush\VAPID;

function create_key()
{
    $filePath = $_SERVER['DOCUMENT_ROOT'] . "/../classes/order_notifies/keys.json";
    // ファイルが存在する場合、鍵を読み取って返す
    if (file_exists($filePath)) {
        $keys = json_decode(file_get_contents($filePath), true);
        return [
            "publickey" => $keys["publicKey"],
            "privatekey" => $keys["privateKey"]
        ];
    }

    // ファイルが存在しない場合、新しい鍵を生成
    $keys = VAPID::createVapidKeys();

    $publickey = $keys["publicKey"];
    $privatekey = $keys["privateKey"];

    // 鍵をファイルに保存
    file_put_contents($filePath, json_encode($keys));

    return [
        "publickey" => $publickey,
        "privatekey" => $privatekey
    ];
}