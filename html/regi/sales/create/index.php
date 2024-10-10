<?php
require_once $_SERVER['DOCUMENT_ROOT']."/regi/users/login_check.php";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品購入フォーム</title>
</head>
<body>
    <h1>商品購入フォーム</h1>
    <h1>TODO: 会計テーブルに合計金額を、詳細テーブルに小計を書く</h1>
    <form action="./create.php" method="post">
        <div id="items">
            <div>
                <label for="product_id_1">商品ID:</label>
                <input type="number" name="product_id[]" id="product_id_1" required>
                <label for="quantity_1">購入数:</label>
                <input type="number" name="quantity[]" id="quantity_1" required>
            </div>
        </div>
        <button type="button" onclick="addItem()">商品を追加</button>
        <button type="submit">送信</button>
    </form>

    <script>
        let itemCount = 1;
        function addItem() {
            itemCount++;
            const newItem = document.createElement('div');
            newItem.innerHTML = `
                <label for="product_id_${itemCount}">商品ID:</label>
                <input type="number" name="product_id[]" id="product_id_${itemCount}" required>
                <label for="quantity_${itemCount}">購入数:</label>
                <input type="number" name="quantity[]" id="quantity_${itemCount}" required>
            `;
            document.getElementById('items').appendChild(newItem);
        }
    </script>
</body>
</html>
