<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/order_notifies/order_notify.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/get_magic_char.php";

function call(Order_Notify $order_notify)
{
    $vapid_subject = $_SERVER['SERVER_NAME'];

    $public_key = "BBG6x0P_tAt4YvkdySqo_3WDtj1NY5luXecfMEcJ4VsLTttSAgABGFT5pEg1sbINrbG2DXbhtdu-O2mMCuYQFa4";
    $private_key = "Wv0l22fh1f_Pz9_Xj6IraGMcdCQp3tZQKsxmBOmfPAs"; // 環境変数またはそのほかの方法で安全に自動生成できるようにしたい

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

    $web_push->sendOneNotification($subscription, $message_encoded);
}