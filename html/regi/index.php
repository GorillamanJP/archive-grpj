<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php"; ?>
<?php session_start(); ?>
<?php
$user = new User();
$user = $user->get_from_id($_SESSION["login"]["user_id"]);
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/products/product.php";
$product_obj = new Product();
$products = $product_obj->get_all();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>レジトップ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <style>
        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            padding-top: 56px;
            /* ナビゲーションバーの高さ分の余白を追加 */
            background-color: #f8f9fa;
            /* 優しいグレー */
        }

        .regia {
            font-family: 'Roboto', sans-serif;
            /* フォントを変更 */
        }

        .container {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            /* フレックスボックスを適用 */
            background-color: #ffffff;
            /* コンテナの背景色を白に設定 */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* コンテナにシャドウを追加 */
            border-radius: 8px;
            /* 角を丸くする */
        }

        .product-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            /* 中央揃えにする */
        }

        .product-grid .product {
            flex: 1 1 calc(16.666% - 1rem);
            /* 横に6個表示 */
            margin: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            word-wrap: break-word;
            /* 長い商品名を折り返す */
            max-width: 200px;
            /* 最大幅を指定 */
        }

        .product-grid .product img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            /* 商品画像を同じ比率で表示 */
            margin-top: 10px;
            /* 画像の上に余白を追加 */
            margin-bottom: 10px;
            /* 画像の下に余白を追加 */
        }


        .content1,
        .content2,
        .content3 {
            flex: 1 1 100%;
            /* デフォルトでは全幅を使う */
            padding: 10px;
            box-sizing: border-box;
            border-radius: 10px;
            /* 枠に丸みをつける */
        }

        .content2,
        .content3 {
            flex: 1 1 50%;
            /* 各コンテンツを半分の幅に設定 */
        }

        @media (max-width: 992px) {
            .product-grid .product {
                flex: 1 1 calc(33.333% - 1rem);
                /* 横に3個表示 */
                max-width: calc(33.333% - 1rem);
                /* 最大幅を指定 */
            }
        }

        @media (max-width: 768px) {
            .product-grid .product {
                flex: 1 1 calc(50% - 1rem);
                /* 横に2個表示 */
                max-width: calc(50% - 1rem);
                /* 最大幅を指定 */
            }

            .content2,
            .content3 {
                flex: 1 1 100%;
                /* 狭い画面では縦並びに戻す */
            }

            .quantity-container {
                flex-direction: row;
                justify-content: center;
            }
        }


        .btn-success {
            background-color: #28a745;
            /* ボタンの背景色を変更 */
            border-color: #28a745;
            /* ボタンのボーダー色を変更 */
        }

        .btn-success:hover {
            background-color: #218838;
            /* ホバー時のボタンの背景色を変更 */
            border-color: #1e7e34;
            /* ホバー時のボーダー色を変更 */
        }

        a {
            text-decoration: none;
            /* リンクの下線を消す */
        }

        .quantity-container {
            display: inline-block;
            text-align: center;
            align-items: center;
            justify-content: center;
            /* 中央揃えにする */
            flex-wrap: nowrap;
            /* 横並びを保持 */
        }

        .quantity-button {
            padding: 2px 5px;
            text-align: center;
            font-size: 12px;
            margin: 2px;
            cursor: pointer;
            transition-duration: 0.4s;
        }

        .quantity-container span {
            display: inline-block;
            margin: 0 5px;
            vertical-align: middle;
        }

        .quantity-column {
            width: 5rem;
            /* 幅を固定 */
            white-space: nowrap;
        }

        .delete-column {
            width: 80px;
            /* 削除部分の幅を固定 */
        }

        #cart-table tbody td {
            text-align: center;
            vertical-align: middle;
        }

        .content1 {
            background-color: #f0f8ff;
            /* 淡い青 */
        }

        .content2 {
            background-color: #ffe4e1;
            /* 淡いピンク */
        }

        .content3 {
            background-color: #e6e6fa;
            /* 淡いラベンダー */
        }

        .product {
            background-color: #faf0e6;
            /* リネン色 */
            border-radius: 10px;
        }

        .product:hover {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5)
                /* ホバーで色濃くする */
        }
    </style>
</head>

<body>
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/navbar.php"; ?>
    <!-- 残りのページ内容 -->
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
                <tbody>
                    <!-- カート商品がここに追加される -->
                </tbody>
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
            <form action="./sales/create/" method="post" id="form">
                <input type="submit" value="支払いへ進む→" class="btn btn-success">
            </form>
        </div>
    </div>
    <script>
        let totalCount = 0;
        let totalPrice = 0;
        let productCounter = 0;
        function addToCart(productName, price, stockQuantity, productId) {
            const cartTable = document.getElementById('cart-table').getElementsByTagName('tbody')[0];
            let existingRow = null;
            for (let row of cartTable.rows) {
                if (row.cells[0] && row.cells[0].innerText === productName) {
                    existingRow = row;
                    break;
                }
            }
            if (existingRow) {
                const currentQuantity = parseInt(existingRow.cells[2].children[1].innerText);
                if (currentQuantity < stockQuantity) {
                    changeQuantity(existingRow.cells[2].children[1], 1, productId, stockQuantity);
                } else {
                    alert('在庫が足りません。');
                }
            } else {
                if (stockQuantity > 0) {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td>${productName}</td><td>${price}円</td><td class="quantity-column"><button class="btn btn-outline-success quantity-button" onclick="changeQuantity(this, -1, ${productId}, ${stockQuantity})">－</button><span>1個</span><button class="btn btn-outline-success quantity-button" onclick="changeQuantity(this, 1, ${productId}, ${stockQuantity})">＋</button></td><td class="delete-column"><button class="btn btn-outline-danger" onclick="removeFromCart(this, ${price}, ${productId})" class="btn btn-danger">削除</button></td>`;
                    cartTable.appendChild(row);
                    updateTotals(price, 1);
                    updateStockDisplay(productId, -1);
                    addHiddenInputs(productId, 1);
                } else {
                    alert('在庫が足りません。');
                }
            }
        }

        function addHiddenInputs(productId, quantity) {
            const form = document.getElementById('form');
            productCounter++;
            const productIdInput = document.createElement('input');
            productIdInput.type = 'hidden';
            productIdInput.name = 'product_id[]';
            productIdInput.id = `product_id_${productCounter}`;
            productIdInput.value = productId;
            const quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = 'quantity[]';
            quantityInput.id = `quantity_${productCounter}`;
            quantityInput.value = quantity;
            form.appendChild(productIdInput);
            form.appendChild(quantityInput);
        }

        function updateForm(productId, quantity) {
            const productIdInputs = document.getElementsByName('product_id[]');
            const quantityInputs = document.getElementsByName('quantity[]');
            for (let i = 0; i < productIdInputs.length; i++) {
                if (productIdInputs[i].value == productId) {
                    quantityInputs[i].value = quantity;
                    return;
                }
            }
            addHiddenInputs(productId, quantity);
        }

        function updateStockDisplay(productId, change) {
            const stockElement = document.getElementById(`stock-${productId}`);
            const currentStock = parseInt(stockElement.innerText.match(/\d+/)[0]);
            stockElement.innerText = `【残${currentStock + change}個】`;
        }

        function updateTotals(price, quantity) {
            totalCount += quantity;
            totalPrice += price;
            const totalCountCell = document.getElementById('total-count');
            const totalPriceCell = document.getElementById('total-price');
            totalCountCell.innerText = `${Math.max(totalCount, 0)}個`;
            totalPriceCell.innerText = `${Math.max(totalPrice, 0)}円`;
        }

        function removeFromCart(button, price, productId) {
            const row = button.parentNode.parentNode;
            const quantity = parseInt(row.cells[2].children[1].innerText);
            row.remove();
            updateTotals(-price * quantity, -quantity);
            updateStockDisplay(productId, quantity); // 在庫を戻す
            const productIdInputs = document.getElementsByName('product_id[]');
            const quantityInputs = document.getElementsByName('quantity[]');
            for (let i = 0; i < productIdInputs.length; i++) {
                if (productIdInputs[i].value == productId) {
                    productIdInputs[i].remove();
                    quantityInputs[i].remove();
                    break;
                }
            }
        }

        function changeQuantity(button, change, productId, stockQuantity) {
            const quantityCell = button.parentNode.children[1];
            let currentQuantity = parseInt(quantityCell.innerText);
            const price = parseInt(button.parentNode.parentNode.cells[1].innerText);
            const newQuantity = currentQuantity + change;
            if (newQuantity < 1) {
                return; // 最小個数を1に制限
            } else if (newQuantity > stockQuantity) {
                alert('在庫が足りません。');
                return;
            }
            quantityCell.innerText = `${newQuantity}個`;
            updateTotals(change * price, change);
            updateStockDisplay(productId, -change); // 在庫数を更新
            updateForm(productId, newQuantity); // フォームを更新
        }
    </script>
</body>

</html>