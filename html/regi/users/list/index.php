<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/user.php";
$user_obj = new User();
$users = $user_obj->get_all();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー管理</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f8f9fa;
            /* 背景色 */
        }

        .container {
            padding-top: 20px;
        }

        h1 {
            font-weight: bold;
            color: #333;
        }

        .table {
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn {
            border-radius: 25px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mt-3">ユーザー一覧</h1>
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <div class="text-center my-3">
            <a href="../create/" class="btn btn-outline-primary btn-lg p-2">ユーザー登録</a>
            <a href="../../products/list/index.php" class="btn btn-outline-success btn-lg p-2">商品一覧へ</a>
        </div>
        <?php if (is_null($users)): ?>
            <p>ユーザーはいません</p>
        <?php else: ?>
            <div class="table-responsive my-4">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-info">
                        <tr>
                            <th>ID</th>
                            <th>ユーザー名</th>
                            <th>更新</th>
                            <th>削除</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        <? foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user->get_id() ?></a></td>
                                <td><?= $user->get_user_name() ?></td>
                                <td>
                                    <form action="../update/" method="post">
                                        <input type="hidden" name="id" value="<?= $user->get_id() ?>">
                                        <input type="submit" value="更新" class="btn btn-outline-primary">
                                    </form>
                                </td>
                                <td>
                                    <!-- 削除ボタン -->
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal" data-id="<?= $user->get_id() ?>">
                                        削除
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
    </div>

    <!-- 削除確認モーダル -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">削除の確認</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                </div>
                <div class="modal-body">
                    本当に削除しますか？
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">削除</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let deleteId;  // 削除するユーザーのIDを保持する変数

        // モーダルが表示されたときに、削除するユーザーのIDを設定
        document.getElementById('deleteModal').addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            deleteId = button.getAttribute('data-id');
        });

        // 「削除」ボタンが押されたら、フォームを作成して送信
        document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
            const form = document.createElement('form');
            form.method = 'post';
            form.action = '../delete/';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id';
            input.value = deleteId;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        });
    </script>
</body>

</html>