<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check_bool.php";
if (!login_check()) {
    http_response_code(403);
    exit();
}

session_start();

if (!isset($_POST["page_offset"]) || !isset($_POST["page_limit"])) {
    http_response_code(400);
    exit();
}

$dt = new DateTime();
$now = $dt->format("Y-m-d H:i:s.u");

$offset = htmlspecialchars($_POST["page_offset"]);
$limit = htmlspecialchars($_POST["page_limit"]);

$last_update = $now;
$_SESSION["regi"]["notify"]["history"]["last_update"] = $last_update;

$data = "";
try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/notifications/notification.php";
    $notification_obj = new Notification();
    $notifications = $notification_obj->gets_range($offset, $limit);
    $page = "./no_list.php";
    if (!is_null($notifications)) {
        $page = "./list.php";
    }
    ob_start();
    require $page;
    $data = ob_get_contents();
    ob_end_clean();

    $all_notification = $notification_obj->get_all();
    $notification_count = 0;
    if(!is_null($all_notification)){
        $notification_count = ceil(count($all_notification) / $limit);
    }
    $page_end = $notification_count;

    echo json_encode([
        "last-update" => date_create($last_update)->format("Y/m/d H:i:s"),
        "table" => $data,
        "page_end" => $page_end,
    ]);
    exit();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["Error" => $e->getMessage()]);
    exit();
}