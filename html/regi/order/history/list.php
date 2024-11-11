<?php foreach ($orders as $order): ?>
    <tr class="clickable-row" data-id="<?= $order->get_order_order()->get_id() ?>">
        <td><?= $order->get_order_order()->get_id() ?></td>
        <td><?= $order->get_order_order()->get_date() ?></td>
        <td>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>品名</th>
                        <th>数量</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order->get_order_details() as $detail): ?>
                        <tr>
                            <td><?= $detail->get_item_name() ?></td>
                            <td><?= $detail->get_quantity() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </td>
        <td>
            <?= $order->get_order_order()->get_is_received() ? "済み" : "まだ" ?>
        </td>
    </tr>
<?php endforeach; ?>