<?php foreach($products as $product): ?>
    <tr>
        <th><?= $product->get_item_name() ?></th>
        <td><?= $product->get_total_sold() ?></td>
        <td><?= $product->get_total_revenue() ?></td>
    </tr>
<?php endforeach; ?>