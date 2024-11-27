<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check_bool.php";
if (!login_check()) {
    http_response_code(403);
    exit();
}

session_start();

if (!isset($_SESSION["regi"]["sales"]["list"]["last_update"]) || $_SESSION["regi"]["sales"]["list"]["last_update"] === "") {
    $_SESSION["regi"]["sales"]["list"]["last_update"] = date("Y/m/d H:i:s");
}

$last_update = $_SESSION["regi"]["sales"]["list"]["last_update"];

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/BaseClass.php";
    $bc = new BaseClass();
    $sql = "SELECT COUNT(*) FROM accountants WHERE date >= :last_update";

    $params = [
        ":last_update" => $last_update,
    ];

    $stmt = $bc->run_query($sql, $params);
    
    $update_count = $stmt->fetchColumn();

    $is_update = false;
    if ($update_count > 0) {
        $is_update = true;
    }
    echo json_encode(["is_update" => $is_update]);
    exit();
} catch (\Throwable $th) {
    http_response_code(500);
    exit();
}
if (!isset($_POST["page_offset"]) || !isset($_POST["page_limit"])) {
    http_response_code(400);
    exit();
}