<?php
session_start();
// メッセージとメッセージタイプがある場合に取得
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';

// メッセージ表示後、セッションから削除
unset($_SESSION['message']);
unset($_SESSION['message_type']);

require_once $_SERVER['DOCUMENT_ROOT'] . "/products/product.php";
$product_obj = new Product();
$products = $product_obj->get_all();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <script>
    // 削除ボタンを押したときに確認ダイアログを表示
    function confirmDelete(form) {
        if (confirm('本当に削除しますか？')) {
            form.submit();  // 確認された場合、フォームを送信
        } else {
            return false;  // キャンセルされた場合、削除処理を中断
        }
    }
    </script>
</head>

<body>
    <h1>商品一覧</h1>
    <?php if ($message): ?>
        <div class="alert alert-<?= htmlspecialchars($message_type) ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (is_null($products)): ?>
        <p>商品はありません</p>
    <?php else: ?>
        <table class="table">
            <tr>
                <th class="align-middle">商品名</th>
                <th class="align-middle">価格</th>
                <th class="align-middle">在庫数</th>
                <th class="align-middle">商品イメージ</th>
                <th class="align-middle"></th>
                <th class="align-middle"></th>
                <th class="align-middle"></th>
            </tr>
            <? foreach ($products as $product): ?>
                <tr>
                    <td class="align-middle"><?= $product->get_item()->get_item_name() ?></td>
                    <td class="align-middle"><?= $product->get_item()->get_price() ?></td>
                    <td class="align-middle"><?= $product->get_stock()->get_quantity() ?></td>
                    <td class="align-middle"><img src="data:image/jpeg;base64,<?= $product->get_item()->get_item_image() ?>" alt="商品画像　ID<?= $product->get_item()->get_id() ?>番"></td>
                    <td class="align-middle">
                        <form action="../update/item/" method="post">
                            <input type="hidden" name="id" id="id" value="<?= $product->get_item()->get_id() ?>">
                            <input type="submit" value="商品更新" class="btn btn-outline-primary">
                        </form>
                    </td>
                    <td class="align-middle">
                        <form action="../update/stock/" method="post">
                            <input type="hidden" name="id" id="id" value="<?= $product->get_stock()->get_id() ?>">
                            <input type="submit" value="入荷処理" btn class="btn btn-outline-success">
                        </form>
                    </td>
                    <td class="align-middle">
                        <form action="../delete/" method="post" onsubmit="return confirmDelete(this)">
                            <input type="hidden" name="id" id="id" value="<?= $product->get_item()->get_id() ?>">
                            <input type="submit" value="削除" class="btn btn-outline-danger">
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>
    <input type="submit" name="submit" onclick="location.href='../create/'" class="btn btn-primary" value="登録画面へ">
</body>
</html>