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
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品登録</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</head>

<body>
    <h1 class="text-center">商品登録</h1>
    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-<?= htmlspecialchars($message_type) ?> alert-dismissible fade show" role="alert">
                <p class="m-0">
                    <?= htmlspecialchars($message) ?>
                    <?php if ($message_details !== ""): ?>
                        <u data-bs-toggle="collapse" data-bs-target="#details" aria-expanded="false"
                            aria-controls="details"><b>詳細</b></u>
                    <?php endif ?>
                </p>
                <div class="collapse" id="details">
                    <?= $message_details ?>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <table class="table table-bordered table-info table-hover ">
            <form action="./create.php" method="post" enctype="multipart/form-data">
                <tr class="form-group">
                    <th class="aligin-middle">商品名</th>
                    <td class="table-secondary"><input type="text" name="item_name" id="item_name" class="form-control"
                            required></td>
                </tr>
                <tr class="form-group">
                    <th class="aligin-middle">価格</th>
                    <td class="table-secondary"><input type="number" name="price" id="price" class="form-control"
                            required></td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">商品イメージ</th>
                    <td class="table-secondary"><img id="image_preview" src="#" alt="商品画像プレビュー"
                            style="display:none; width: 200px;"></td>
                </tr>
                <tr class="form-group">
                    <th class="aligin-middle">画像選択</th>
                    <td class="table-secondary"><input type="file" name="item_image" id="item_image" accept="image/jpeg"
                            class="form-control" required></td>
                </tr>
        </table>
        <div class="text-center">
            <input type="submit" name="submit" class="btn btn-outline-primary btn-lg" value="登録">
            <a href="../list/index.php" class="btn btn-outline-secondary btn-lg">戻る</a>
        </div>
        </form>
    </div>
    <script>
        document.getElementById('item_image').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const preview = document.getElementById('image_preview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>