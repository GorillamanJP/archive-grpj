<?php
function check_update(string $last_update, int $last_products_count)
{
    try {
        $password = getenv("DB_PASSWORD");
        $db_name = getenv("DB_DATABASE");
        $dsn = "mysql:host=mariadb;dbname={$db_name}";
        $pdo = new PDO($dsn, "root", $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $sql_stocks = "SELECT COUNT(*) FROM stocks WHERE last_update >= :input_last_update";

        $stmt_stocks = $pdo->prepare($sql_stocks);
        $stmt_stocks->bindValue(":input_last_update", $last_update, PDO::PARAM_STR);
        $stmt_stocks->execute();

        $update_stocks = $stmt_stocks->fetchColumn();

        if ($update_stocks > 0) {
            return generate_updated_page();
        }


        $sql_items_update = "SELECT COUNT(*) FROM items WHERE last_update >= :input_last_update";

        $stmt_items_update = $pdo->prepare($sql_items_update);
        $stmt_items_update->bindValue(":input_last_update", $last_update, PDO::PARAM_STR);
        $stmt_items_update->execute();

        $update_items = $stmt_items_update->fetchColumn();

        if ($update_items > 0) {
            return generate_updated_page();
        }


        $sql_items_count = "SELECT COUNT(*) FROM items";

        $stmt_items_count = $pdo->prepare($sql_items_count);

        $stmt_items_count->execute();

        $count_items = $stmt_items_count->fetchColumn();

        if ($count_items != $last_products_count) {
            return generate_updated_page();
        }

        http_response_code(200);
        return "";
    } catch (\Throwable $e) {
        http_response_code(500);
        return "Exception! {$e->getMessage()}";
    }
}

function generate_updated_page()
{
    require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/products/product.php";
    $products = new Product();
    $products = $products->get_all();
    http_response_code(200);
    if (is_null($products)) {
        return "
<tr>
    <td colspan='5'>
        <input type='hidden' id='products_count' name='products_count' value=0>
        <h2 class='text-center'>商品はありません。</h2>
        <p class='text-center'><a href='../create/'>新たに商品を登録しましょう！</a></p>
    </td>
</tr>
        ";
    }
    $products_count = count($products);
    $html_text = "<input type='hidden' id='products_count' name='products_count' value={$products_count}>";
    foreach ($products as $product) {
        $html_text .= "
<tr>
    <td>
        <img src='data:image/jpeg;base64,{$product->get_item()->get_item_image()}'
            alt='商品画像　ID{$product->get_item()->get_id()}番' class='img-fluid img-thumbnail'>
    </td>
    <td>{$product->get_item()->get_item_name()}</td>
    <td>{$product->get_item()->get_price()}</td>
    <td>{$product->get_stock()->get_quantity()}</td>
    <td>
        <table class='container'>
            <tr>
                <td>
                    <form action='../update/item/' method='post'>
                        <input type='hidden' name='id' id='id' value='{$product->get_item()->get_id()}'>
                        <input type='submit' value='更新' class='btn btn-outline-primary round-button'>
                    </form>
                </td>
            </tr>
            <tr>
                <td>
                    <form action='../update/stock/' method='post'>
                        <input type='hidden' name='id' id='id' value='{$product->get_stock()->get_id()}'>
                        <input type='submit' value='入荷' btn class='btn btn-outline-success round-button'>
                    </form>
                </td>
            </tr>
            <tr>
                <td>
                    <!-- 削除ボタン -->
                    <button type='button' class='btn btn-outline-danger round-button'
                        data-bs-toggle='modal' data-bs-target='#deleteModal'
                        data-id={$product->get_item()->get_id()}
                        data-name={$product->get_item()->get_item_name()}>削除</button>
                </td>
            </tr>
        </table>
    </td>
</tr>";
    }
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
