<?php
require_once $_SERVER['DOCUMENT_ROOT']."/regi/users/login_check.php";
?>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <link rel="stylesheet" href="/common/create.css">
</head>

<body>
    <h1 class="text-center my-3">ユーザー登録</h1>
    <div class="container">
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <form action="./create.php" method="post">
            <table class="table table-bordered table-info table-hover ">
                <tr class="form-group">
                    <th class="align-middle">ユーザー名</th>
                    <td class="table-secondary">
                        <input type="text" name="user_name" id="user_name" class="form-control" required>
                    </td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">パスワード</th>
                    <td class="table-secondary">
                        <input type="password" name="password" id="password" class="form-control" required>
                    </td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">パスワード（確認）</th>
                    <td class="table-secondary">
                        <input type="password" name="password_re_input" id="password_re_input" class="form-control" required>
                    </td>
                </tr>
            </table>

            <div class="text-center">
                <input type="submit" class="btn btn-outline-primary btn-lg" value="登録">
                <a href="../list/" class="btn btn-outline-secondary btn-lg">戻る</a>
            </div>

        </form>
    </div>
</body>

</html>