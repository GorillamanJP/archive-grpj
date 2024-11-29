<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
session_start();

unset($_SESSION["regi"]["order"]["id"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";
$order_obj = new Order();
$orders = $order_obj->get_all();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レジ/モバイルオーダー/受け取り待ち一覧</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <link rel="stylesheet" href="/common/list.css">
</head>


<body>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/navbar.php"; ?>
    <!-- コンテナ開始 -->
    <div class="container mt-4">
        <h1 class="text-center mb-4">受け取り待ち一覧</h1>

        <!-- ページネーション準備 -->
        <p class="text-center my-3" style="font-size: 1.2em;">最終更新時刻:<span id="last-update">0000/0/0 00:00:00</span></p>

        <div class="text-center mb-3">
            <a href="../../" class="btn btn-outline-success btn-lg-custom p-2 mx-1">レジ画面</a>
            <a href="../history/" class="btn btn-outline-success btn-lg-custom p-2 mx-1">履歴一覧</a>
        </div>
        <table class="table table-striped table-bordered table-hover text-center align-middle table-primary">
            <thead>
                <tr>
                    <th>注文番号</th>
                    <th>注文日時</th>
                    <th>詳細</th>
                    <th>状態</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody id="table">
                <tr>
                    <td colspan="5">
                        <h3>読み込み中…</h3>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- キャンセル確認モーダル -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">キャンセル確認</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    本当にこの注文を取り消しますか？
                    <form id="confirmCancelForm" action="../cancel/" method="post">
                        <input type="hidden" name="order_id" id="modalOrderId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                    <button type="submit" form="confirmCancelForm" class="btn btn-danger">取消</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cancelModal = document.getElementById('cancelModal');
            cancelModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const orderId = button.getAttribute('data-id');
                const modalOrderId = document.getElementById('modalOrderId');
                modalOrderId.value = orderId;
            });
        });
    </script>

    <!-- 通知領域 -->
    <div id="notifications" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    <!-- 通知領域　ここまで -->
    <script src="/regi/notify/check_notify.js"></script>
</body>
<script src="/common/check_update_common.js"></script>


</html>