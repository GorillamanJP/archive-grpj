<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";
$order_obj = new Order();
$orders = $order_obj->get_all();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>モバイル注文準備中</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/common/list.css">
</head>

<body>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/navbar.php"; ?>
    <!-- コンテナ開始 -->
    <div class="container mt-4">
        <h1 class="text-center mb-4">モバイル注文準備中</h1>

        <!-- ページネーション準備 -->
        <p class="text-center my-3" style="font-size: 1.2em;">最終更新時刻:<span id="last-update">0000/0/0 00:00:00</span></p>

        <div class="text-center mb-3">
            <a href="../../" class="btn btn-outline-success btn-lg-custom p-2 mx-1">レジ画面へ</a>
        </div>
        <h1>モバイル待機列</h1>
        <table class="table table-striped table-bordered table-hover text-center align-middle">
            <thead>
                <tr>
                    <th>注文番号</th>
                    <th>注文日時</th>
                    <th>詳細</th>
                    <th>受け取り</th>
                </tr>
            </thead>
            <tbody id="refresh">
                <tr>
                    <td colspan="4">
                        <h3>読み込み中…</h3>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- 通知領域 -->
    <div id="notifications" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    <script src="/regi/notify/check_notify.js"></script>
    <!-- 通知領域　ここまで -->
</body>
<script src="/common/check_update_common.js"></script>

</html>