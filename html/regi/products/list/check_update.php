<?php
function check_update(string $last_update, int $last_products_count)
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


        $sql_items_count = "SELECT COUNT(*) FROM items";

        $stmt_items_count = $pdo->prepare($sql_items_count);

        $stmt_items_count->execute();

        $count_items = $stmt_items_count->fetchColumn();

        if ($count_items > $last_products_count) {
            return generate_updated_page("新しい商品が登録されました！");
        } else if ($count_items < $last_products_count) {
            return generate_updated_page("商品が削除されました！");
        }


        $sql_items_update = "SELECT COUNT(*) FROM items WHERE last_update >= :input_last_update";

        $stmt_items_update = $pdo->prepare($sql_items_update);
        $stmt_items_update->bindValue(":input_last_update", $last_update, PDO::PARAM_STR);
        $stmt_items_update->execute();

        $update_items = $stmt_items_update->fetchColumn();

        if ($update_items > 0) {
            return generate_updated_page("商品内容が変わりました！");
        }


        $sql_stocks = "SELECT COUNT(*) FROM stocks WHERE last_update >= :input_last_update";

        $stmt_stocks = $pdo->prepare($sql_stocks);
        $stmt_stocks->bindValue(":input_last_update", $last_update, PDO::PARAM_STR);
        $stmt_stocks->execute();

        $update_stocks = $stmt_stocks->fetchColumn();

        if ($update_stocks > 0) {
            return generate_updated_page("在庫数に変化がありました！");
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
    require_once $_SERVER['DOCUMENT_ROOT']."/../classes/products/product.php";
    $products = new Product();
    $products = $products->get_all();

    http_response_code(200);

    ob_start();
    if (is_null($products)) {
        require "./list_not_product.php";
    } else {
        $products_count = count($products);
        require "./products_list_tbody.php";
    }
    $html_text = ob_get_contents();
    ob_end_clean();

    return $html_text;
}

if (!isset($_POST['last_update']) || !isset($_POST["last_products_count"])) {
    http_response_code(400);
    echo "Bad Argument!";
    exit();
}
$last_update = $_POST['last_update'];
$last_products_count = $_POST["last_products_count"];
echo check_update($last_update, $last_products_count);
