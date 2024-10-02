<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/users/user.php";
$user_obj = new User();
$users = $user_obj->get_all();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>ユーザー一覧</h1>
    <?php if (is_null($users)): ?>
        <p>ユーザーはいません</p>
    <?php else: ?>
        <table>
            <? foreach ($users as $user): ?>
                <tr>
                    <td><a href="../update/?id=<?= $user->get_id() ?>">ID: <?= $user->get_id() ?></a></td>
                    <td><?= $user->get_user_name() ?></td>
                    <td><a href="../delete/?id=<?= $user->get_id() ?>">削除</a></td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>
</body>

</html>