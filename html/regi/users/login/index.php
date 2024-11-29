<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<style>
    .alert-info {
        text-align: center;
        background-color: #d1ecf1; /* 背景色 */
        border-color: #bee5eb; /* ボーダー色 */
        color: #0c5460; /* 文字色 */
        border-radius: 8px; /* 丸みを持たせた角 */
        max-width: 600px; /* 最大横幅を指定 */
        width: 90%; /* 横幅を親要素の90%に設定 */
        margin: 0 auto; /* 左右自動マージンで中央揃え */
        font-weight: bold; /* リンク文字を太字に */
        padding: 5px ; /* 上下10px、左右25pxの余白 */
        margin-top: 20px; /* 上部の余白 */
    }
</style>




<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レジログイン</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
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
        </div><br>
        <div class="alert-info">
            <p><a href="/order/">モバイルオーダーはこちら</a></p>
        </div>
    </div>
</body>

</html>