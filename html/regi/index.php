<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
session_start();

// メッセージとメッセージタイプを取得
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
    <title>レジトップ</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="app">
        <div class="sidebar">
            <a href="#"><i class="fas fa-shopping-cart"></i>
                <p>モバイルオーダー</p>
            </a>
            <a href="#"><i class="fas fa-list-alt"></i>
                <p>売上一覧</p>
            </a>
            <a href="products/list"><i class="fas fa-cubes"></i>
                <p>商品管理</p>
            </a>
            <div class="sidefoot">
                <p class="useracc">XX XX(アカウント名)</p>
                <button>ログアウト</button>
            </div>
        </div>

        <div class="content1">
            <h1>商品一覧</h1>
            <section class="image-text-block">
                <div class="imgs">
                    <?php if (empty($products)): ?>
                        <p>なにも登録されていません</p>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="img1" id="product-<?= $product->get_item()->get_id() ?>"
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
            <table id="cart-table">
                <tr>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>個数</th>
                    <th>削除</th>
                </tr>
            </table>
        </div>

        <div class="content3">
            <h1>会計</h1>
            <table class="sum">
                <tr>
                    <th>合計品数</th>
                    <th>合計金額</th>
                </tr>
                <tr>
                    <td id="total-count">0個</td>
                    <td id="total-price">0円</td>
                </tr>
            </table>
            <br><br>
            <form action="./sales/create/" method="post" id="form">
                <input type="submit" value="支払いへ進む→" class="butt">
            </form>
        </div>

        <div class="purchase-form">
            <script>let totalCount = 0; // 合計品数
                let totalPrice = 0; // 合計金額
                let productCounter = 0; // 商品の連番

                function addToCart(productName, price, stockQuantity, productId) {
                    const cartTable = document.getElementById('cart-table');
                    let existingRow = null;

                    // 既存の商品を確認
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
                            row.innerHTML = `
                <td>${productName}</td>
                <td>${price}円</td>
                <td>
                    <button class="quantity-button" onclick="changeQuantity(this, -1, ${productId}, ${stockQuantity})">－</button>
                    <span>1個</span>
                    <button class="quantity-button" onclick="changeQuantity(this, 1, ${productId}, ${stockQuantity})">＋</button>
                </td>
                <td><button onclick="removeFromCart(this, ${price}, ${productId})">削除</button></td>
            `;
                            cartTable.appendChild(row);
                            updateTotals(price, 1);
                            updateStockDisplay(productId, -1);
                            addHiddenInputs(productId, 1); // 新しい商品をカートに追加
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

                    // 新しいinput:hiddenを追加
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

                    // フォーム要素を削除
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
        </div>
    </div>
</body>

</html>