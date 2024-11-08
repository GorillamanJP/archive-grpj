<?php foreach ($orders as $order): ?>
    <tr>
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
            <form action="../receive/" method="post">
                <input type="hidden" name="order_id" id="order_id" value="<?= $order->get_order_order()->get_id() ?>">
                <input type='submit' value='受け取り' btn class='btn btn-outline-success round-button'>
            </form>
        </td>
    </tr>
<?php endforeach; ?>