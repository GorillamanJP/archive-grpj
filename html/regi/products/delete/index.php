<?php
session_start();
if (!isset($_POST["id"]) || $_POST["id"] === "") {
    $_SESSION["message"] = "商品のIDが指定されていません。";
    $_SESSION["message_details"] = "このメッセージが出る場合、内部のバグの可能性がありますので、「何を」「どのように」したらエラーが出たのかを開発者までお伝えください。\nご不便、ご迷惑をおかけして申し訳ありませんが、ご協力をお願いします。";
    $_SESSION["message_type"] = "danger";
    session_write_close();
    header("Location ../../");
    exit();
}

$id = htmlspecialchars($_POST["id"]);

require_once $_SERVER['DOCUMENT_ROOT']."/../classes/products/product.php";

try {
    $product = new Product();
    $product = $product->get_from_item_id($id);
    $product->delete();
    // 成功メッセージをセッションに保存
    $_SESSION['message'] = '商品が正常に削除されました。';
    $_SESSION['message_type'] = 'success';
}catch(\Throwable $e){
    // エラーメッセージをセッションに保存
    $_SESSION['message'] = 'エラーが発生しました。';
    $_SESSION["message_details"] = $e->getMessage();
    $_SESSION['message_type'] = 'danger';
}
// 商品一覧ページへリダイレクト
session_write_close();
header('Location: ../list/');
exit();