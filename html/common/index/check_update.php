<?php
session_start();

$dt = new DateTime();
$now = $dt->format("Y-m-d H:i:s.u");

if (!isset($_SESSION["list"]["last_update"]) || $_SESSION["list"]["last_update"] === "") {
    $_SESSION["list"]["last_update"] = $now;
}

$last_update = $_SESSION["list"]["last_update"];

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/notifications/notification.php";
    $notification = new Notification();
    $notification_count = $notification->gets_notifications_after($last_update);

    $is_update = !is_null($notification_count);

    echo json_encode(["is_update" => $is_update]);
    exit();
} catch (\Throwable $th) {
    http_response_code(500);
    // echo json_encode(["Error" => $th->getMessage()]);
    exit();
}