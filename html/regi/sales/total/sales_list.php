<?php foreach($products as $product): ?>
    <tr>
        <th><?= $product->get_item_name() ?><?= $product->get_delete_flag() ? "（削除済み）" : ""?></th>
        <td><?= $product->get_total_sold() ?></td>
        <td><?= $product->get_total_revenue() ?></td>
    </tr>
<?php endforeach; ?>