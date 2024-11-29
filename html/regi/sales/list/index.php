<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/products/product.php";
$product = new Product();
$products = $product->get_all();

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/details/detail.php";
$detail = new Detail();

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="title">会計一覧</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <link rel="stylesheet" href="/common/list.css">
    <style>
        .clickable-row {
            cursor: pointer;
        }

        .custom-background {
            padding: 20px;
            border-radius: 5px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .loading-message {
            color: #888;
            font-style: italic;
        }
    </style>
</head>

<body>
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/navbar.php"; ?>
    <div class="container custom-background">
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <h1 class="text-center mb-4">会計一覧</h1>
        <p class="text-center my-3" style="font-size: 1.2em;">最終更新時刻:<span id="last-update">0000/0/0 00:00:00</span></p>
        <div class="text-center mb-3">
            <a href="../total/" class="btn btn-outline-primary btn-lg-custom p-2 mx-1">総売上一覧</a>
            <a href="../../" class="btn btn-outline-success btn-lg-custom p-2 mx-1">レジ画面へ</a>
            <div class="alert alert-info mt-2">
                詳細を見るには、項目を押してください。
            </div>
        </div>
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

        <table class="table table-secondary table-bordered table-hover text-center align-middle">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>購入日</th>
                    <th>詳細</th>
                    <th>会計者</th>
                </tr>
            </thead>
            <tbody id="accountants_table">
                <tr>
                    <td colspan="4">
                        <h2>読み込み中…</h2>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="notifications" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    <script>
        function run_custom_function() {
            set_tap_detail();
        }
    </script>
    <script src="/regi/notify/check_notify.js"></script>
    <script src="/common/set_tap_detail.js"></script>
    <script src="/common/check_update_common.js"></script>
    <script src="/common/pagination.js"></script>
</body>

</html>