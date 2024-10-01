<?php
$id = htmlspecialchars($_GET["id"]);
require_once $_SERVER['DOCUMENT_ROOT'] . "/users/user.php";

$user = new User();
$user = $user->get_from_id($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="update.php" method="post">
        <input type="hidden" name="id" value="<?= $user->get_id() ?>"></p>
        <p><input type="text" name="username" id="username" value="<?= $user->get_username() ?>">ユーザー名</p>
        <p><input type="password" name="password" id="password">新しいパスワード</p>
        <p>パスワードはそのままの場合でも入力しなおしてください。</p>
        <input type="submit" value="更新">
    </form>
</body>
</html>