<?php foreach ($products as $product): ?>
    <div class="product" id="product-<?= $product->get_item_id() ?>">
        <img src="data:image/jpeg;base64,<?= $product->get_item_image() ?>"
            alt="<?= htmlspecialchars($product->get_item_name()) ?>">
        <p id="product-<?= $product->get_item_id() ?>-name"><?= htmlspecialchars($product->get_item_name()) ?></p>
        <p id="product-<?= $product->get_item_id() ?>-name"><?= $product->get_price() ?>円</p>
        <p>【残<span id="product-<?= $product->get_item_id()?>-stock" data-original-stock="<?= $product->get_buy_available_count() ?>"><?= $product->get_buy_available_count() ?></span>個】</p>
    </div>
<?php endforeach; ?>