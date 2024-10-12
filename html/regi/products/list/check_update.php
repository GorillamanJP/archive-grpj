<?php
function check_update(string $last_update)
{
    try {
        $password = getenv("DB_PASSWORD");
        $db_name = getenv("DB_DATABASE");
        $dsn = "mysql:host=mariadb;dbname={$db_name}";
        $pdo = new PDO($dsn, "root", $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql_items = "SELECT COUNT(*) FROM items WHERE last_update > :input_last_update";

        $stmt_items = $pdo->prepare($sql_items);
        $stmt_items->bindValue(":input_last_update", $last_update, PDO::PARAM_STR);
        $stmt_items->execute();

        $update_items = $stmt_items->fetchColumn();

        $sql_stocks = "SELECT COUNT(*) FROM stocks WHERE last_update > :input_last_update";

        $stmt_stocks = $pdo->prepare($sql_stocks);
        $stmt_stocks->bindValue(":input_last_update", $last_update, PDO::PARAM_STR);
        $stmt_stocks->execute();

        $update_stocks = $stmt_stocks->fetchColumn();

        if ($update_items > 0 || $update_stocks > 0) {
            http_response_code(200);
            return json_encode(true);
        } else {
            http_response_code(200);
            return json_encode(false);
        }
    } catch (\Throwable $e) {
        http_response_code(500);
        return json_encode(["error" => "Exception: {$e}"]);
    }
}

if(!isset($_POST["last_update"])){
    http_response_code(400);
    echo json_encode(["error"=> "Bad Argument"]);
    exit();
}
$last_update = $_POST["last_update"];
echo check_update($last_update);