<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レジ/通知/通知履歴</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <link rel="stylesheet" href="/common/list.css">
    <style>
        .clickable-row {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/navbar.php"; ?>
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
    <!-- コンテナ開始 -->
    <div class="container mt-4">
        <h1 class="text-center mb-4">通知履歴</h1>

        <!-- ページネーション準備 -->
        <p class="text-center my-3" style="font-size: 1.2em;">最終更新時刻:<span id="last-update">0000/0/0 00:00:00</span></p>

        <!-- ページネーション -->
        <div class="d-flex justify-content-center">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item">
                        <button class="page-link" id="page_prev" aria-label="Previous">
                            <span aria-hidden="true">&lt;</span>
                        </button>
                    </li>
                    <li class="page-item">
                        <span class="page-link" id="page_no">1</span>
                    </li>
                    <li class="page-item">
                        <span class="page-link">/</span>
                    </li>
                    <li class="page-item">
                        <span class="page-link" id="page_end">1</span>
                    </li>
                    <li class="page-item">
                        <button class="page-link" id="page_next" aria-label="Next">
                            <span aria-hidden="true">&gt;</span>
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- ページネーション　終わり -->

        <table class="table table-striped table-bordered table-hover text-center align-middle table-primary">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>タイトル</th>
                    <th>内容</th>
                </tr>
            </thead>
            <tbody id="table">
            </tbody>
        </table>
    </div>
    <div id="notifications" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    <!-- コンテナ終了 -->
    <script src="/regi/notify/check_notify.js"></script>
    <script src="/common/check_update_common.js"></script>
    <script src="/common/pagination.js"></script>
</body>

</html>