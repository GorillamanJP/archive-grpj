<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レジログイン</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</head>

<body>
    <h1 class="text-center mt-3">レジログイン</h1>
    <div class="container">
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-6">
                <form id="login-form" class="form" action="./login.php" method="post">
                    <table class="table table-bordered table-info table-hover ">
                        <tr class="form-group">
                            <th class="align-middle text-center">ユーザー名</th>
                            <td class="table-secondary"><input type="text" name="user_name" id="user_name"
                                    class="form-control" required></td>
                        </tr>
                        <tr class="form-group">
                            <th class="align-middle text-center">パスワード</th>
                            <td class="table-secondary"><input type="password" name="password" id="password"
                                    class="form-control" required></td>
                        </tr>
                    </table>
                    <div class="text-center">
                        <input type="submit" name="submit" class="btn btn-primary btn-lg" value="ログイン">
                    </div>
                </form>
            </div>
        </div>
        <p><a href="/order/">モバイルオーダーはこちら</a></p>
    </div>
</body>

</html>