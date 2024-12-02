<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/temp_purchase_details/temp_purchase_detail.php"; ?>
<?php foreach ($products as $product): ?>
    <?php $tpd = new Temp_Purchases_Detail(); ?>
    <?php $ptq = $tpd->get_exists_temp_quantity_from_item_id($product->get_item_id()) ?>
    <div class="product" id="product-<?= $product->get_item_id() ?>">
        <img src="data:image/jpeg;base64,<?= $product->get_item_image() ?>"
            alt="<?= htmlspecialchars($product->get_item_name()) ?>">
        <p id="product-<?= $product->get_item_id() ?>-name"><?= htmlspecialchars($product->get_item_name()) ?></p>
        <p id="product-<?= $product->get_item_id() ?>-name"><?= $product->get_price() ?>円</p>
        <p>
            <?php if ($product->get_buy_available_count() - 11 - $ptq <= 0): ?>
                売り切れ
            <p style="display: none;">【残<span id="product-<?= $product->get_item_id() ?>-stock"
                    data-original-stock="0">0</span>個】</p>
        <?php else: ?>
            【残<span id="product-<?= $product->get_item_id() ?>-stock"
                data-original-stock="<?= $product->get_buy_available_count() - 11 - $ptq ?>"><?= $product->get_buy_available_count() - 11 - $ptq ?></span>個】
        <?php endif ?>
        </p>
    </div>
<?php endforeach; ?>