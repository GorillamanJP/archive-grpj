<?php foreach ($sales as $sale): ?>
    <tr class="clickable-row" data-id="<?= $sale->get_accountant()->get_id() ?>">
        <td><?= $sale->get_accountant()->get_id() ?></td>
        <td><?= $sale->get_accountant()->get_formatted_date() ?></td>
        <td>
            <table class="table table-striped table-bordered">
                <tr>
                    <th>品名</th>
                    <th>数量</th>
                </tr>
                <?php foreach ($sale->get_details() as $detail): ?>
                    <tr>
                        <td><?= $detail->get_item_name() ?></td>
                        <td><?= $detail->get_quantity() ?></td>
                    </tr>
                <?php endforeach ?>
            </table>
        </td>
        <td><?= $sale->get_accountant()->get_accountant_user_name() ?></td>
    </tr>
<?php endforeach ?>