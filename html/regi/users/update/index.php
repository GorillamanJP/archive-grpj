<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
session_start();
// メッセージとメッセージタイプがある場合に取得
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$message_details = isset($_SESSION["message_details"]) ? $_SESSION["message_details"] : "";
$message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';

// メッセージ表示後、セッションから削除
unset($_SESSION['message']);
unset($_SESSION["message_details"]);
unset($_SESSION['message_type']);


$id = htmlspecialchars($_POST["id"]);
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/user.php";

$user = new User();
$user = $user->get_from_id($id);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー情報更新</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</head>

<body>
    <h1 class="text-center mt-3">ユーザー情報更新</h1>
    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-<?= htmlspecialchars($message_type) ?> alert-dismissible fade show" role="alert">
                <p class="m-0">
                    <?= htmlspecialchars($message) ?>
                    <u data-bs-toggle="collapse" data-bs-target="#details" aria-expanded="false"
                        aria-controls="details"><b>詳細</b></u>
                </p>
                <div class="collapse" id="details">
                    <?= $message_details ?>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <table class="table table-bordered table-info table-hover">
            <form action="update.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $user->get_id() ?>"></p>
                <tr class="form-group">
                    <th class="align-middle">ユーザー名</th>
                    <td class="table-secondary">
                        <input type="text" name="user_name" id="user_name" value="<?= $user->get_user_name() ?>"
                            class="form-control" required>
                    </td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">新しいパスワード</th>
                    <td class="table-secondary">
                        <input type="password" name="password" id="password" class="form-control" required>
                    </td>
                </tr>
                <p class="text-center">パスワードはそのままの場合でも入力しなおしてください。</p>
        </table>
        <div class="text-center">
            <input type="submit" value="更新" class="btn btn-outline-primary">
            <a href="../list/index.php" class="btn btn-outline-secondary">戻る</a>
        </div>
        </form>
    </div>
</body>

</html>