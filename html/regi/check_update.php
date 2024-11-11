<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check_bool.php";
if (!login_check()) {
    http_response_code(403);
    exit();
}

session_start();

if (!isset($_SESSION["regi"]["list"]["last_update"]) || $_SESSION["regi"]["list"]["last_update"] === "") {
    $_SESSION["regi"]["list"]["last_update"] = date("Y/m/d H:i:s");
}

$last_update = $_SESSION["regi"]["list"]["last_update"];

try {
    $password = getenv("DB_PASSWORD");
    $db_name = getenv("DB_DATABASE");
    $dsn = "mysql:host=mariadb;dbname={$db_name}";
    $pdo = new PDO($dsn, "root", $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql_items = "SELECT COUNT(*) FROM items WHERE last_update >= :last_update";

    $stmt_items = $pdo->prepare($sql_items);

    $stmt_items->bindValue(":last_update", $last_update);

    $stmt_items->execute();

    $update_count = intval($stmt_items->fetchColumn());

    
    $sql_accountants = "SELECT COUNT(*) FROM accountants WHERE date >= :last_update";

    $stmt_accountants = $pdo->prepare($sql_accountants);

    $stmt_accountants->bindValue(":last_update", $last_update);

    $stmt_accountants->execute();

    $update_count += intval($stmt_accountants->fetchColumn());

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