<?php
session_start();
if (!isset($_POST["id"]) || $_POST["id"] === "") {
    $_SESSION["message"] = "商品のIDが指定されていません。\nこのメッセージが出る場合、内部のバグの可能性がありますので、「何を」「どのように」したらエラーが出たのかを開発者までお伝えください。\nご不便をおかけして申し訳ありませんが、ご協力をお願いします。";
    $_SESSION["message_type"] = "danger";
    session_write_close();
    header("Location ../../");
    exit();
}

$id = htmlspecialchars($_POST["id"]);

$ok = true;
$_SESSION["message"] = "";
if (!isset($_POST["item_name"]) || $_POST["item_name"] === "") {
    # 商品名
    $_SESSION["message"] .= "「商品名」";
    $ok = false;
}
if (!isset($_POST["price"]) || $_POST["price"] === "") {
    # 価格
    $_SESSION["message"] .= "「価格」";
    $ok = false;
}
if (!isset($_FILES["new_item_image"]["tmp_name"]) || $_FILES["new_item_image"]["tmp_name"] === "") {
    # 画像
    $_SESSION["message"] .= "「商品画像」";
    $ok = false;
}
if ($ok) {
    # 全部入ってる
    $item_name = htmlspecialchars($_POST["item_name"]);
    $price = htmlspecialchars($_POST["price"]);
    $item_image = $_FILES["new_item_image"]["tmp_name"];

    require_once $_SERVER['DOCUMENT_ROOT']."/../classes/items/item.php";

    try {
        $item = new Item();
        $item = $item->get_from_id($id);
        $item = $item->update($item_name, $price, $item_image);
        // 成功時のメッセージをセッションに保存
        $_SESSION['message'] = "商品情報が正常に更新されました。";
        $_SESSION['message_type'] = "success";  // 成功メッセージ用
        session_write_close();
        header("Location: ../../list/");
        exit();
    } catch (Throwable $e) {
        // 失敗時のメッセージをセッションに保存
        $_SESSION['message'] = "エラーが発生しました。";
        $_SESSION["message_details"] = $e->getMessage();
        $_SESSION['message_type'] = "danger";  // エラーメッセージ用
    }
} else {
    $_SESSION["message"] .= "の項目が空になっています。";
    $_SESSION["message_type"] = "danger";
}
session_write_close();
?>
<form action="./" method="post" id="post_form">
    <input type="hidden" name="id" id="id" value="<?= $id ?>">
</form>
<script>document.getElementById("post_form").submit();</script>