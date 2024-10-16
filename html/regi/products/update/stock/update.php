<?php
session_start();
if (!isset($_POST["id"]) || $_POST["id"] === "") {
    $_SESSION["message"] = "商品のIDが指定されていません。\nこのメッセージが出る場合、内部のバグの可能性がありますので、「何を」「どのように」したらエラーが出たのかを開発者までお伝えください。\nご不便をおかけして申し訳ありませんが、ご協力をお願いします。";
    $_SESSION["message_type"] = "danger";
    header("Location ../../");
    exit();
}

$id = htmlspecialchars($_POST["id"]);

$ok = true;
$_SESSION["message"] = "";
if (!isset($_POST["add_quantity"]) || $_POST["add_quantity"] === "") {
    $_SESSION["message"] .= "「入荷数」";
    $ok = false;
}

if ($ok) {
    $add_quantity = htmlspecialchars($_POST["add_quantity"]);

    require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/stocks/stock.php";

    try {
        $stock = new Stock();
        $stock->start_transaction();
        $stock = $stock->get_from_id($id);
        $now_quantity = $stock->get_quantity();
        // 在庫が0未満にならないようにチェック
        $new_quantity = $now_quantity + $add_quantity;
        if ($new_quantity < 0) {
            throw new Exception("在庫が0未満になるため、更新できません。");
        }

        $stock = $stock->update($new_quantity);
        $stock->commit();
        $_SESSION["message"] = "在庫が追加されました。";
        $_SESSION["message_type"] = "success";
        header("Location: ../../list/");
        exit();
    } catch (\Throwable $e) {
        $_SESSION["message"] = "エラーが発生しました。";
        $_SESSION["message_details"] = $e->getMessage();
        $_SESSION["message_type"] = "danger";
    }
} else {
    $_SESSION["message"] .= "の項目が空になっています。";
    $_SESSION["message_type"] = "danger";
}
?>
<form action="./" method="post" id="post_form">
    <input type="hidden" name="id" id="id" value="<?= $id ?>">
</form>
<script>document.getElementById("post_form").submit();</script>