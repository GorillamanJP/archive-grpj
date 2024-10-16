<?php
session_start();
if ($_POST["id"] === "") {
    $_SESSION["message"] = "商品のIDが指定されていません。\nこのメッセージが出る場合、内部のバグの可能性がありますので、「何を」「どのように」したらエラーが出たのかを開発者までお伝えください。\nご不便をおかけして申し訳ありませんが、ご協力をお願いします。";
    $_SESSION["message_type"] = "danger";
    header("Location ../../");
    exit();
}

$id = htmlspecialchars($_POST["id"]);

if (!($_POST["item_name"] === "" || $_POST["price"] === "" || $_FILES["new_item_image"]["tmp_name"] === "")) {
    $item_name = htmlspecialchars($_POST["item_name"]);
    $price = htmlspecialchars($_POST["price"]);
    $item_image = $_FILES["new_item_image"]["tmp_name"];

    require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/items/item.php";

    try {
        $item = new Item();
        $item = $item->get_from_id($id);
        $item = $item->update($item_name, $price, $item_image);
        // 成功時のメッセージをセッションに保存
        $_SESSION['message'] = "商品情報が正常に更新されました。";
        $_SESSION['message_type'] = "success";  // 成功メッセージ用
        header("Location: ../../list/");
        exit();
    } catch (\Throwable $e) {
        // 失敗時のメッセージをセッションに保存
        $_SESSION['message'] = "エラーが発生しました: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";  // エラーメッセージ用
    }
} else {
    $_SESSION["message"] = "入力情報が不足しています。";
    $_SESSION["message_type"] = "danger";
}
?>
<form action="./" method="post" id="post_form">
    <input type="hidden" name="id" id="id" value="<?= $id ?>">
</form>
<script>document.getElementById("post_form").submit();</script>