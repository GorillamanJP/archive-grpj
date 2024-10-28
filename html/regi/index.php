<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php"; ?>
<?php session_start(); ?>
<?php
$user = new User();
$user = $user->get_from_id($_SESSION["login"]["user_id"]);
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/products/product.php";
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
    <style>
        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            padding-top: 56px;
            /* ナビゲーションバーの高さ分の余白を追加 */
        }

        .container {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            /* フレックスボックスを適用 */
        }

        .product-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .product-grid .product {
            flex: 1 1 calc(16.666% - 1rem);
            /* 横に6個表示 */
            margin: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .product-grid .product img {
            max-width: 100px;
            /* イメージを小さくする */
        }

        .content1,
        .content2,
        .content3 {
            flex: 1 1 100%;
            /* デフォルトでは全幅を使う */
            padding: 10px;
            box-sizing: border-box;
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
            }
        }

        @media (max-width: 768px) {
            .product-grid .product {
                flex: 1 1 calc(50% - 1rem);
                /* 横に2個表示 */
            }

            .content2,
            .content3 {
                flex: 1 1 100%;
                /* 狭い画面では縦並びに戻す */
            }
        }

        @media (max-width: 576px) {
            .product-grid .product {
                flex: 1 1 calc(100% - 1rem);
                /* 横に1個表示 */
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <button onclick="toggleNavbar()" class="navbar-toggler" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-shopping-cart"></i> モバイルオーダー</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./sales/list/"><i class="fas fa-list-alt"></i> 会計一覧</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./products/list/"><i class="fas fa-cubes"></i> 商品管理</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./users/list/"><i class="fas fa-user"></i> ユーザー管理</a>
                    </li>
                    <!-- 新たにユーザー名とログアウトボタンをここに追加 -->
                    <li class="nav-item">
                        <p class="nav-link mb-0 mr-3 text-white"><?= $user->get_user_name() ?></p>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-danger"><a href="./users/logout/" style="color: #fff;">ログアウト</a></button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="content1">
            <h1>商品一覧</h1>
            <section class="image-text-block">
                <div class="product-grid">
                    <?php if (empty($products)): ?>
                        <p>なにも登録されていません</p>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="product" id="product-<?= $product->get_item()->get_id() ?>"
                                onclick="addToCart('<?= htmlspecialchars($product->get_item()->get_item_name()) ?>', <?= $product->get_item()->get_price() ?>, <?= $product->get_stock()->get_quantity() ?>, <?= $product->get_item()->get_id() ?>)">
                                <img src="data:image/jpeg;base64,<?= $product->get_item()->get_item_image() ?>"
                                    alt="<?= htmlspecialchars($product->get_item()->get_item_name()) ?>">
                                <p class="product-name"><?= htmlspecialchars($product->get_item()->get_item_name()) ?></p>
                                <p class="price"><?= $product->get_item()->get_price() ?>円</p>
                                <p id="stock-<?= $product->get_item()->get_id() ?>">
                                    【残<?= $product->get_stock()->get_quantity() ?>個】</p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </div>
        <div class="content2">
            <h1>選択商品</h1>
            <table id="cart-table" class="table">
                <thead>
                    <tr>
                        <th>商品名</th>
                        <th>価格</th>
                        <th>個数</th>
                        <th>削除</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- カート商品がここに追加される -->
                </tbody>
            </table>
        </div>
        <div class="content3">
            <h1>会計</h1>
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
                    row.innerHTML = `<td>${productName}</td><td>${price}円</td><td><button class="quantity-button" onclick="changeQuantity(this, -1, ${productId}, ${stockQuantity})">－</button><span>1個</span><button class="quantity-button" onclick="changeQuantity(this, 1, ${productId}, ${stockQuantity})">＋</button></td><td><button onclick="removeFromCart(this, ${price}, ${productId})" class="btn btn-danger">削除</button></td>`;
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

        function toggleNavbar() {
            const navbar = document.getElementById('navbarNav');
            navbar.classList.toggle('show');
        }
    </script>
</body>

</html>