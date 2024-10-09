<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインテスト</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</head>

<body>
    <?php if (isset($_SESSION["error"])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><?= $_SESSION["error"]["message"] ?></strong>
            <?php unset($_SESSION["error"]);?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>
    <div id="login">
        <h3 class="text-center text-white pt-5">Login form</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="./login.php" method="post">
                            <h3 class="text-center">レジログイン</h3>
                            <div class="form-group">
                                <label for="user_name">ユーザー名</label><br>
                                <input type="text" name="user_name" id="user_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label><br>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <div>
                                <input type="submit" name="submit" class="btn btn-primary" value="ログイン">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section>
        <div class="text-center">
            <button class="btn btn-success">レジ登録</button>
        </div>
    </section>
</body>

</html>