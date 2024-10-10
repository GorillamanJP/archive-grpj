<?php
require_once $_SERVER['DOCUMENT_ROOT']."/regi/users/login_check.php";
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レジトップ</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <!-- アイコン -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    </head>

<body>
    <div class="app">
        <div class="sidebar">
            <a href="#"><i class="fas fa-shopping-cart"></i><p>モバイルオーダー</p></a>
            <a href="#"><i class="fas fa-list-alt"></i><p>売上一覧</p></a>
            <a href="#"><i class="fas fa-cubes"></i><p>商品管理</p></a>
            <div class="sidefoot">
                <p class="useracc">XX XX(アカウント名)</p>
                <button>ログアウト</button>
            </div>
            
        </div>

        <div class="content1">
            <h1>商品一覧</h1>
            <section class="image-text-block">
                <div class="imgs">
                    <div class="img1" onclick="addToCart('商品A', 100)">
                        <img src="sampleA.jpg" alt="Sample A">
                        <p class="price">１００円</p>
                    </div>
                    <div class="img1" onclick="addToCart('商品B', 200)">
                        <img src="sampleB.jpg" alt="Sample B">
                        <p class="price">２００円</p>
                    </div>
                    <div class="img1" onclick="addToCart('商品C', 300)">
                        <img src="sampleC.jpg" alt="Sample C">
                        <p class="price">３００円</p>
                    </div>
                    <div class="img1" onclick="addToCart('商品D', 400)">
                        <img src="sampleA.jpg" alt="Sample D">
                        <p class="price">４００円</p>
                    </div>
                    <div class="img1" onclick="addToCart('商品E', 500)">
                        <img src="sampleA.jpg" alt="Sample D">
                        <p class="price">５００円</p>
                    </div>
                    <div class="img1" onclick="addToCart('商品D', 400)">
                        <img src="sampleA.jpg" alt="Sample D">
                        <p class="price">４００円</p>
                    </div>
                    <div class="img1" onclick="addToCart('商品D', 400)">
                        <img src="sampleA.jpg" alt="Sample D">
                        <p class="price">４００円</p>
                    </div>
                    <div class="img1" onclick="addToCart('商品D', 400)">
                        <img src="sampleA.jpg" alt="Sample D">
                        <p class="price">４００円</p>
                    </div>
                    <div class="img1" onclick="addToCart('商品D', 400)">
                        <img src="sampleA.jpg" alt="Sample D">
                        <p class="price">４００円</p>
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
            <button class="butt">支払いへ進む→</button>
        </div>

        <div class="purchase-form">
            <script>
                let totalCount = 0; // 合計品数
                let totalPrice = 0; // 合計金額

                function addToCart(productName, price) {
                    const cartTable = document.getElementById('cart-table');
                    let existingRow = null;

                    // 既存の商品を探す
                    for (let row of cartTable.rows) {
                        if (row.cells[0] && row.cells[0].innerText === productName) {
                            existingRow = row;
                            break;
                        }
                    }

                    if (existingRow) {
                        // 既にカートにある場合、個数を増やす
                        changeQuantity(existingRow.cells[2].children[1], 1);
                    } else {
                        // 新しい商品を追加
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${productName}</td>
                            <td>${price}円</td>
                            <td>
                                <button class="quantity-button" onclick="changeQuantity(this, -1)">－</button>
                                <span>1個</span>
                                <button class="quantity-button" onclick="changeQuantity(this, 1)">＋</button>
                            </td>
                            <td><button onclick="removeFromCart(this)">削除</button></td>
                        `;
                        cartTable.appendChild(row);

                        // 合計の更新
                        updateTotals(price, 1);
                    }
                }

                function updateTotals(price, quantity) {
                    // 合計を更新
                    totalCount += quantity;
                    totalPrice += price;

                    // 表示を更新
                    const totalCountCell = document.getElementById('total-count');
                    const totalPriceCell = document.getElementById('total-price');

                    totalCountCell.innerText = `${Math.max(totalCount, 0)}個`; // 最小0にする
                    totalPriceCell.innerText = `${Math.max(totalPrice, 0)}円`; // 最小0にする
                }

                function removeFromCart(button) {
                    const row = button.parentNode.parentNode; // ボタンの親の親は行
                    const price = parseInt(row.children[1].innerText); // 価格を取得
                    const quantity = parseInt(row.children[2].children[1].innerText); // 現在の数量を取得

                    // 行を削除
                    row.remove();

                    // 合計の更新
                    updateTotals(-price * quantity, -quantity);
                }

                function changeQuantity(button, change) {
                    const quantityCell = button.parentNode.children[1]; // 個数を表示するセル
                    let currentQuantity = parseInt(quantityCell.innerText);

                    // 個数を変更
                    const price = parseInt(button.parentNode.parentNode.cells[1].innerText); // 価格を取得
                    const oldQuantity = currentQuantity;

                    currentQuantity += change;

                    // 最小個数を1に制限
                    if (currentQuantity < 1) {
                        currentQuantity = 1;
                    }

                    quantityCell.innerText = `${currentQuantity}個`;

                    // 合計の更新
                    const priceDifference = (currentQuantity - oldQuantity) * price;

                    // 合計を更新
                    updateTotals(priceDifference, currentQuantity - oldQuantity);
                }
            </script>
        </div>
    </div>
</body>

</html>
