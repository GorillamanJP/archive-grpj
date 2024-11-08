<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/captcha/require_captcha.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/protects/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/receive/is_receive.php";
session_start();
unset($_SESSION["order"]["order_items"]);
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/products/product.php";
$product_obj = new Product();
$products = $product_obj->get_all();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>モバイルオーダー</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/common/regi.css">
</head>

<body>
    <h1 class="text-center my-3">モバイルオーダー</h1>
    <div class="container regia">
        <div class="content1">
            <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
            <h1 class="text-center">商品一覧</h1>
            <section class="image-text-block">
                <div class="product-grid">
                    <?php if (empty($products)): ?>
                        <p>なにも登録されていません</p>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="product" id="product-<?= $product->get_item_id() ?>"
                                onclick="addToCart('<?= htmlspecialchars($product->get_item_name()) ?>', <?= $product->get_price() ?>, <?= $product->get_now_stock() ?>, <?= $product->get_item_id() ?>)">
                                <img src="data:image/jpeg;base64,<?= $product->get_item_image() ?>"
                                    alt="<?= htmlspecialchars($product->get_item_name()) ?>">
                                <p class="product-name"><?= htmlspecialchars($product->get_item_name()) ?></p>
                                <p class="price"><?= $product->get_price() ?>円</p>
                                <p id="stock-<?= $product->get_item_id() ?>">
                                    【残<?= $product->get_now_stock() ?>個】</p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </div>
        <div class="content2">
            <h1 class="text-center">選択商品</h1>
            <table id="cart-table" class="table">
                <thead>
                    <tr>
                        <th>商品名</th>
                        <th>価格</th>
                        <th>個数</th>
                        <th>取消</th>
                    </tr>
                </thead>
                <tbody> <!-- カート商品がここに追加される --> </tbody>
            </table>
        </div>
        <div class="content3">
            <h1 class="text-center">会計</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>合計品数</th>
                        <th>合計金額</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="total-count">0個</td>
                        <td id="total-price">0円</td>
                    </tr>
                </tbody>
            </table>
            <form action="./create/" method="post" id="form">
                <input type="submit" value="確認へ進む→" class="btn btn-success">
            </form>
        </div>
    </div>
    <script src="/common/regi.js"></script>
</body>

</html>