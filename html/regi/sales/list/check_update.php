<?php
function check_update(string $last_update)
{
    if ($last_update === "0000/0/0 00:00:00") {
        return generate_updated_page("読み込みが完了しました！");
    }
    try {
        $password = getenv("DB_PASSWORD");
        $db_name = getenv("DB_DATABASE");
        $dsn = "mysql:host=mariadb;dbname={$db_name}";
        $pdo = new PDO($dsn, "root", $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $sql = "SELECT COUNT(*) FROM accountants WHERE date >= :last_update";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":last_update", $last_update, PDO::PARAM_STR);

        $stmt->execute();

        $count_items = $stmt->fetchColumn();

        if ($count_items > 0) {
            return generate_updated_page("新しい会計がありました！");
        }


        http_response_code(200);
        return "";
    } catch (\Throwable $e) {
        http_response_code(500);
        return "Exception! {$e->getMessage()}";
    }
}

function generate_updated_page(string $update_msg)
{
    require_once $_SERVER['DOCUMENT_ROOT']."/../classes/sales/sale.php";
    $sales = new Sale();
    $sales = $sales->get_all();

    ob_start();
    if (is_null($sales)) {
        require "./list_not_sale.php";
    } else {
        require "./sales_list_tbody.php";
    }
    $html_text = ob_get_contents();
    ob_end_clean();

    http_response_code(200);
    return $html_text;
}

if (!isset($_POST['last_update']) || $_POST["last_update"] === "") {
    http_response_code(400);
    echo "Bad Argument!";
    exit();
}
$last_update = $_POST['last_update'];
echo check_update($last_update);
