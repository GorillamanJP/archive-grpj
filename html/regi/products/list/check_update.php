<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check_bool.php";
if (!login_check()) {
    http_response_code(403);
    exit();
}

session_start();

if (!isset($_SESSION["regi"]["product"]["list"]["last_update"]) || $_SESSION["regi"]["product"]["list"]["last_update"] === "") {
    $_SESSION["regi"]["product"]["list"]["last_update"] = date("Y/m/d H:i:s");
}

$last_update = $_SESSION["regi"]["product"]["list"]["last_update"];

try {
    $password = getenv("DB_PASSWORD");
    $db_name = getenv("DB_DATABASE");
    $dsn = "mysql:host=mariadb;dbname={$db_name}";
    $pdo = new PDO($dsn, "root", $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT COUNT(*) FROM items WHERE last_update >= :last_update";

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(":last_update", $last_update);

    $stmt->execute();

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