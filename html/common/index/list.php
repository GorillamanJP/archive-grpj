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