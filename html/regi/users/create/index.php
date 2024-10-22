<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/common/create.css">
</head>

<body>
    <h1 class="text-center mt-3">ユーザー登録</h1>
    <div class="container">
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <form action="./create.php" method="post">
            <table class="table table-bordered table-info table-hover ">
                <tr class="form-group">
                    <th class="align-middle">ユーザー名</th>
                    <td class="table-secondary"><input type="text" name="user_name" id="user_name" class="form-control"
                            required></td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">パスワード</th>
                    <td class="table-secondary"><input type="password" name="password" id="password"
                            class="form-control" required></td>
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