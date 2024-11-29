<?php foreach ($notifications as $notification): ?>
    <tr>
        <td><?= $notification->get_sent_date() ?></td>
        <td><?= $notification->get_title() ?></td>
        <td><?= $notification->get_message() ?></td>
    </tr>
<?php endforeach; ?>