<?php
require_once $_SERVER['DOCUMENT_ROOT']."/regi/users/login_check.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../classes/users/user.php";
$user_obj = new User();
$users = $user_obj->get_all();

session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レジ/ユーザー管理</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <link rel="stylesheet" href="/common/list.css">
    <style>
        .custom-background {
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/navbar.php"; ?>
    <!-- 残りのページ内容 -->
    <div class="container custom-background">
        <h1 class="text-center mb-4">ユーザー管理</h1>
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <div class="text-center my-3">
            <a href="../create/" class="btn btn-outline-primary btn-lg p-2 mx-1">ユーザー登録</a>
            <a href="../../" class="btn btn-outline-success btn-lg p-2 mx-1">レジ画面</a>
        </div>
        <?php if (is_null($users)): ?>
            <p>ユーザーはいません</p>
        <?php else: ?>
            <div class="table-responsive mb-3">
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
                                        <input type="submit" value="更新" class="btn btn-outline-primary round-button">
                                    </form>
                                </td>
                                <td>
                                    <!-- 削除ボタン -->
                                    <button type="button" class="btn btn-outline-danger round-button" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal" data-id="<?= $user->get_id() ?>">
                                        削除
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <p class="text-center"><a href="../logout/"><button type="button" class="btn btn-secondary">ログアウト</button></a>
            </p>
        <?php endif ?>
    </div>

    <div id="notifications" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    <script src="/regi/notify/check_notify.js"></script>

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