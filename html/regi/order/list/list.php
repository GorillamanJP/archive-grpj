<?php foreach ($orders as $order): ?>
    <tr class="clickable-row" data-id="<?= $order->get_order_order()->get_id() ?>">
        <td><?= $order->get_order_order()->get_id() ?></td>
        <td><?= $order->get_order_order()->get_date() ?></td>
        <td>
            <table class="table table-bordered">
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
                <tbody>
            </table>
        </td>
        <td>
            <ul class="list-group list-group-flush">
                <?php if ($order->get_order_order()->get_is_call()): ?>
                    <li class="list-group-item">呼出中</li>
                <?php endif ?>
                <?php if ($order->get_order_order()->get_is_cancel()): ?>
                    <li class="list-group-item">キャンセル済</li>
                <?php endif ?>
                <?php if ($order->get_order_order()->get_is_received()): ?>
                    <li class="list-group-item">受取済</li>
                <?php endif ?>
            </ul>
        </td>
        <td>
            <form action="../show/" method="post">
                <input type="hidden" name="id" id="id" value="<?= $order->get_order_order()->get_id() ?>">
                <button type="submit" btn class='btn btn-outline-info round-button'>詳細</button>
            </form>
            <form action="../receive/" method="post">
                <input type="hidden" name="order_id" id="order_id" value="<?= $order->get_order_order()->get_id() ?>">
                <button type="submit" btn class='btn btn-outline-success round-button'>受取</button>
            </form>
            <form action="../call/" method="post">
                <input type="hidden" name="order_id" id="order_id" value="<?= $order->get_order_order()->get_id() ?>">
                <button type="submit" class="btn btn-outline-primary round-button">呼出</button>
            </form>
            <form action="../cancel/" method="post" class="cancel-form">
                <input type="hidden" name="order_id" id="order_id" value="<?= $order->get_order_order()->get_id() ?>">
                <button type="button" class="btn btn-outline-danger round-button" data-bs-toggle="modal"
                    data-bs-target="#cancelModal" data-id="<?= $order->get_order_order()->get_id() ?>">取消</button>
            </form>

        </td>
    </tr>
<?php endforeach; ?>