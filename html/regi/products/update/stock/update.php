<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_alert_with_form.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/verify_int_value.php";

session_start();

if (!isset($_POST["id"]) || $_POST["id"] === "") {
    redirect_with_error("../../list/", "商品のIDが指定されていません。", "", "warning");
}

$id = htmlspecialchars($_POST["id"]);

if (!isset($_POST["add_quantity"]) || $_POST["add_quantity"] === "") {
    redirect_with_error_with_form("./", "在庫の追加数の項目が空になっています。", "", "warning", $_POST);
}

$add_quantity = htmlspecialchars($_POST["add_quantity"]);

if (verify_int_value($add_quantity) == false) {
    redirect_with_error_with_form("./", "数値エラー", "価格または在庫の数値が小数になっているか、値が大きすぎる可能性があります。", "danger", $_POST);
}

if (intval($add_quantity) < 0) {
    redirect_with_error_with_form("./", "在庫を減らすことはできません。", "", "warning", $_POST);
}


require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/products/product.php";

try {
    $product = new Product();
    $product = $product->get_from_item_id($id);
    $now_quantity = $product->get_now_stock();
    // 在庫が0未満にならないようにチェック
    $new_quantity = $now_quantity + $add_quantity;
    if ($new_quantity < 0) {
        throw new Exception("在庫が0未満になるため、更新できません。");
    }

    $product = $product->get_stock()->create($product->get_item_id(), $add_quantity);

    redirect_with_error("../../list/", "在庫が追加されました。", "", "success");
} catch (Throwable $e) {
    redirect_with_error_with_form("./", "エラーが発生しました。", $e->getMessage(), "danger", $_POST);
}