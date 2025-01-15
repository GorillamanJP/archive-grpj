<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/order_notifies/order_notify.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/get_magic_char.php";

function call(Order_Notify $order_notify)
{
    $vapid_subject = "https://" . $_SERVER['HTTP_HOST'];

    $public_key = $order_notify->get_public_key();
    $private_key = $order_notify->get_private_key();

    $auth = [
        "VAPID" => [
            "subject" => $vapid_subject,
            "publicKey" => $public_key,
            "privateKey" => $private_key
        ]
    ];

    $web_push = new WebPush($auth);

    $message = json_encode(
        [
            "title" => "モバイルオーダー",
            "message" => "呼び出されています！",
            "url" => "./?magic_char=" . get_magic_char()
        ]
    );

    $message_encoded = base64_encode($message);

    $subscription = Subscription::create([
        "endpoint" => $order_notify->get_endpoint(),
        "publicKey" => $order_notify->get_user_public_key(),
        "authToken" => $order_notify->get_user_auth_token()
    ]);

    $result = $web_push->sendOneNotification($subscription, $message_encoded);

    if(!$result->isSuccess()){
        throw new Exception("プッシュ通知の送信に失敗しました。", -1);
    }
}