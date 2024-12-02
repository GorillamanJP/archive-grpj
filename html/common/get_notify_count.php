<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check_bool.php";
if (!login_check()) {
    http_response_code(403);
    exit();
}

session_start();

if (!isset($_SESSION["regi"]["notify"]["history"]["last_update"]) || $_SESSION["regi"]["notify"]["history"]["last_update"] === "") {
    $_SESSION["regi"]["notify"]["history"]["last_update"] = date("Y/m/d H:i:s");
}

$last_update = $_SESSION["regi"]["notify"]["history"]["last_update"];

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/notifications/notification.php";
    $notification = new Notification();
    $notifications = $notification->gets_notifications_after($last_update);
    $count = 0;
    if (!is_null($notifications)) {
        $count = count($notifications);
    }
    echo json_encode(["notify_count" => $count]);
    session_write_close();
    exit();
} catch (Throwable $th) {
    http_response_code(500);
    echo json_encode(["Error" => $th->getMessage()]);
    session_write_close();
    exit();
}