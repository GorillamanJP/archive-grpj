<?php
session_start();
// メッセージとメッセージタイプがある場合に取得
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';

// メッセージ表示後、セッションから削除
unset($_SESSION['message']);
unset($_SESSION['message_type']);

require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/products/product.php";
$product_obj = new Product();
$products = $product_obj->get_all();

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
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
    <div class="container">
        <h1 class="text-center mt-3">商品一覧</h1>
        <p class="text-end">最終更新時刻:<span id="last-update-time">99:99:99</span></p>
        <?php if ($message): ?>
            <div class="alert alert-<?= htmlspecialchars($message_type) ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div class="text-center my-3">
            <a href="../create/" class="btn btn-outline-primary btn-lg p-2">商品登録</a>
            <a href="" class="btn btn-outline-success btn-lg p-2">レジ画面へ</a>
        </div>
        <?php if (is_null($products)): ?>
            <h2 class="text-center">商品はありません。</h2>
            <p class="text-center"><a href="../create/">新たに商品を登録しましょう！</a></p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-info">
                        <tr>
                            <th>商品イメージ</th>
                            <th>商品名</th>
                            <th>価格</th>
                            <th>在庫数</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        <? foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <img src="data:image/jpeg;base64,<?= $product->get_item()->get_item_image() ?>"
                                        alt="商品画像　ID<?= $product->get_item()->get_id() ?>番" class="img-fluid img-thumbnail">
                                </td>
                                <td><?= $product->get_item()->get_item_name() ?></td>
                                <td><?= $product->get_item()->get_price() ?></td>
                                <td><?= $product->get_stock()->get_quantity() ?></td>
                                <td>
                                    <table class="container">
                                        <tr>
                                            <td>
                                                <form action="../update/item/" method="post">
                                                    <input type="hidden" name="id" id="id"
                                                        value="<?= $product->get_item()->get_id() ?>">
                                                    <input type="submit" value="更新" class="btn btn-outline-primary">
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <form action="../update/stock/" method="post">
                                                    <input type="hidden" name="id" id="id"
                                                        value="<?= $product->get_stock()->get_id() ?>">
                                                    <input type="submit" value="入荷" btn class="btn btn-outline-success">
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <form action="../delete/" method="post" onsubmit="return confirmDelete(this)">
                                                    <input type="hidden" name="id" id="id"
                                                        value="<?= $product->get_item()->get_id() ?>">
                                                    <input type="submit" value="削除" class="btn btn-outline-danger">
                                                </form>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
    </div>
</body>

</html>