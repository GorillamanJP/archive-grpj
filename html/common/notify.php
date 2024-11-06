<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check_bool.php";
if (!login_check()) {
    http_response_code(403);
    exit();
}

if (!isset($_SESSION["notify"]["last_update"]) || $_SESSION["notify"]["last_update"] === "") {
    $_SESSION["notify"]["last_update"] = date("Y-m-d H:i:s");
}
$datetime = $_SESSION["notify"]["last_update"];
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/notifications/notification.php";
$notification = new Notification();
$notifications = $notification->gets_notifications_after($datetime);

if (!is_null($notifications)) {
    $notifications_array = array();
    foreach ($notifications as $notify) {
        $notifications_array[] = [
            'title' => $notify->get_title(),
            'message' => $notify->get_message(),
            'sent_date' => $notify->get_sent_date()
        ];
    }
    echo json_encode($notifications_array);
    $_SESSION["notify"]["last_update"] = date("Y-m-d H:i:s");
}
session_write_close();
exit();
