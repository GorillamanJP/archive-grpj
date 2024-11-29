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
            <ul class="list-group list-group-flush">
                <?php if ($order->get_order_order()->get_is_call()): ?>
                    <li class="list-group-item">呼び出し中</li>
                <?php endif ?>
                <?php if ($order->get_order_order()->get_is_cancel()): ?>
                    <li class="list-group-item">キャンセル済</li>
                <?php endif ?>
                <?php if ($order->get_order_order()->get_is_received()): ?>
                    <li class="list-group-item">受け取り済</li>
                <?php endif ?>
            </ul>
        </td>
    </tr>
<?php endforeach; ?>