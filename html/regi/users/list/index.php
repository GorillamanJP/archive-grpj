<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/users/user.php";
$user_obj = new User();
$users = $user_obj->get_all();
?>
<!DOCTYPE html>
<html lang="ja">

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
                    <td>
                        <form action="../update/" method="post">
                            <input type="hidden" name="id" value="<?= $user->get_id() ?>">
                            <input type="submit" value="更新">
                        </form>
                    </td>
                    <td>
                        <form action="../delete/" method="post">
                            <input type="hidden" name="id" value="<?= $user->get_id() ?>">
                            <input type="submit" value="削除">
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>
</body>

</html>