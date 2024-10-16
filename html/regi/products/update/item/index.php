<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
session_start();
// メッセージとメッセージタイプがある場合に取得
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';

// メッセージ表示後、セッションから削除
unset($_SESSION['message']);
unset($_SESSION['message_type']);

$id = htmlspecialchars($_POST["id"]);
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/products/product.php";

$product = new Product();
$product = $product->get_from_item_id($id);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品更新</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</head>

<body>
    <h1 class="text-center">商品更新</h1>
    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-<?= htmlspecialchars($message_type) ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <table class="table table-bordered table-info table-hover ">
            <form action="update.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $product->get_item()->get_id() ?>">
                <tr class="form-group">
                    <th class="align-middle">商品名</th>
                    <td class="table-secondary"><input type="text" name="item_name" id="item_name"
                            value="<?= $product->get_item()->get_item_name() ?>" class="form-control"></td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">価格</th>
                    <td class="table-secondary"><input type="number" name="price" id="price"
                            value="<?= $product->get_item()->get_price() ?>" class="form-control"></td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">商品イメージ</th>
                    <td class="table-secondary"><img
                            src="data:image/jpeg;base64,<?= $product->get_item()->get_item_image() ?>"
                            alt="商品画像　ID<?= $product->get_item()->get_id() ?>番" id="now_item_image"
                            style="width: 200px;"></td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">画像選択</th>
                    <td class="table-secondary"><input type="file" name="new_item_image" id="new_item_image"
                            accept="image/jpeg" class="form-control"></td>
                </tr>
        </table>
        <div class="text-center">
            <input type="submit" value="更新" class="btn btn-outline-primary">
            <a href="../../list/index.php" class="btn btn-outline-secondary">戻る</a>
        </div>
        </form>
    </div>
</body>
<script src="./set_now_image.js"></script>

</html>