<?php foreach ($products as $product): ?>
    <tr>
    <td class="product-image">
    <img src='data:image/jpeg;base64,<?= $product->get_item_image() ?>'
         alt='商品画像　ID<?= $product->get_item_id() ?>番' class='img-fluid img-thumbnail'>
</td>

        <td><?= $product->get_item_name() ?></td>
        <td><?= $product->get_price() ?></td>
        <td><?= $product->get_now_stock() ?></td>
        <td>
            <table class='container'>
                <tr>
                    <td>
                        <form action='../update/item/' method='post'>
                            <input type='hidden' name='id' id='id' value='<?= $product->get_item_id() ?>'>
                            <input type='submit' value='更新' class='btn btn-outline-primary round-button'>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <form action='../update/stock/' method='post'>
                            <input type='hidden' name='id' id='id' value='<?= $product->get_item_id() ?>'>
                            <input type='submit' value='入荷' btn class='btn btn-outline-success round-button'>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <!-- 削除ボタン -->
                        <button type='button' class='btn btn-outline-danger round-button' data-bs-toggle='modal'
                            data-bs-target='#deleteModal' data-id=<?= $product->get_item_id() ?>
                            data-name=<?= $product->get_item_name() ?>>削除</button>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
<?php endforeach; ?>