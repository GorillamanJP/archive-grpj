<?php
require_once $_SERVER['DOCUMENT_ROOT']."/regi/users/login_check.php";
?>
<?php session_start(); ?>
<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../classes/products/product.php";
$product_obj = new Product();
$products = $product_obj->get_all();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="title">レジ/商品管理</title>
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
        <h1 class="text-center mb-3">商品管理</h1>
        <p class="text-center my-4" style="font-size: 1.2em;">最終更新時刻:<span id="last-update">0000/0/0 00:00:00</span></p>
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <div class="text-center mb-3">
            <a href="../create/" class="btn btn-outline-primary btn-lg-custom p-2 mx-1">商品登録</a>
            <a href="../../" class="btn btn-outline-success btn-lg-custom p-2 mx-1">レジ画面へ</a>
        </div>
        <div class="table-responsive my-4">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-info">
                    <tr>
                        <th>商品イメージ</th>
                        <th>商品名</th>
                        <th>価格</th>
                        <th>在庫数</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody class="table-light" id="table">
                    <tr>
                        <td colspan="5">
                            <h2>読み込み中…</h2>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- 通知領域 -->
    <div id="notifications" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    <script src="/regi/notify/check_notify.js"></script>
    <!-- 通知領域　ここまで -->
    <!-- 削除確認モーダル -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">削除の確認</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold fs-4 text-center"><span id="deleteItemName"></span>を本当に削除しますか？</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">削除</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let deleteId;  // 削除する商品のIDを保持する変数
        // モーダルが表示されたときに、削除する商品のIDと名前を設定
        document.getElementById('deleteModal').addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            deleteId = button.getAttribute('data-id');
            const itemName = button.getAttribute('data-name');
            document.getElementById('deleteItemName').textContent = itemName;
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
    <script src="/common/check_update_common.js"></script>
</body>

</html>