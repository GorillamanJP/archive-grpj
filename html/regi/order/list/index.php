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
            <a href="../../" class="btn btn-outline-success btn-lg-custom p-2 mx-1">レジ画面へ</a>
            <a href="../history/" class="btn btn-outline-success btn-lg-custom p-2 mx-1">履歴一覧へ</a>
        </div>
        <table class="table table-striped table-bordered table-hover text-center align-middle table-primary">
            <thead>
                <tr>
                    <th>注文番号</th>
                    <th>注文日時</th>
                    <th>詳細</th>
                    <th>ステータス</th>
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
    <!-- 通知領域 -->
    <div id="notifications" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    <!-- 通知領域　ここまで -->
    <script>
        function run_custom_function() {
            set_tap_detail();
        }
    </script>
    <script src="/regi/notify/check_notify.js"></script>
    <script src="/common/set_tap_detail.js"></script>
</body>
<script src="/common/check_update_common.js"></script>


</html>