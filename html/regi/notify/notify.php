<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check_bool.php";
if (!login_check()) {
    http_response_code(403);
    exit();
}

session_start();

if (!isset($_SESSION["notify"]["last_update"]) || $_SESSION["notify"]["last_update"] === "") {
    $_SESSION["notify"]["last_update"] = date("Y-m-d H:i:s");
}
$datetime = $_SESSION["notify"]["last_update"];
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/notifications/notification.php";
$notification = new Notification();
$notifications = $notification->gets_notifications_after($datetime);

$response = [
    'hasData' => !is_null($notifications) && count($notifications) > 0,
    'notifications' => []
];

if ($response['hasData']) {
    foreach ($notifications as $notify) {
        $response['notifications'][] = [
            'title' => $notify->get_title(),
            'message' => $notify->get_message(),
            'sent_date' => $notify->get_sent_date()
        ];
    }
    $_SESSION["notify"]["last_update"] = date("Y-m-d H:i:s");
}

echo json_encode($response);
session_write_close();
exit();
