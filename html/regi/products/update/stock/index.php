<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
session_start();
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";

if (!isset($_POST["id"]) || $_POST["id"] === "") {
    redirect_with_error("../../list/", "商品IDが指定されていません。", "", "warning");
}

$id = htmlspecialchars($_POST["id"]);
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/products/product.php";

$product = new Product();
$product = $product->get_from_item_id($id);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レジ/商品管理/入荷処理</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
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

        .custom-alert {
            position: absolute;
            top: 150px;
            /* 入荷処理の下に表示 */
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050;
            display: none;
            padding: 10px 20px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <h1 class="text-center mt-3 my-3">入荷処理</h1>
    <div class="container">
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <form id="modal_required_form" action="update.php" method="post" onsubmit="return handleSubmit(event);">
            <table class="table table-bordered table-info table-hover">
                <tr class="form-group">
                    <th class="align-middle">商品イメージ</th>
                    <td class="table-secondary"><img src="data:image/jpeg;base64,<?= $product->get_item_image() ?>"
                            alt="商品画像　ID<?= $product->get_item_id() ?>番" id="now_item_image" style="width: 200px;"></td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">商品名</th>
                    <td class="table-secondary"><?= $product->get_item_name() ?></td>
                </tr>
                <tr class="form-group">
                    <th>現在の在庫数</th>
                    <td class="table-secondary" id="currentStock"><?= $product->get_now_stock() ?></td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">入荷数</th>
                    <td class="table-secondary">
                        <input type="number" name="add_quantity" id="add_quantity" class="form-control" min="0"
                            required>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="id" value="<?= $product->get_item_id() ?>">
            <div class="text-center">
                <button type="submit" id="submit_button" class="btn btn-outline-primary btn-lg">更新</button>
                <a href="../../list/" class="btn btn-outline-secondary btn-lg">戻る</a>
            </div>
        </form>
    </div>
    <!-- カスタムアラートの要素 -->
    <div id="customAlert" class="custom-alert"></div>

    <!-- 更新確認モーダル -->
    <div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">更新の確認</h5>
                    <button type="button" id="close_button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="閉じる"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold fs-4">本当に更新しますか？</p>
                    <div>
                        <p class="fs-5">現在の在庫数: <span id="confirmCurrentStock" class="badge bg-secondary fs-5"></span>
                        </p>
                        <p class="fs-5">更新後の在庫数: <span id="confirmNewStock" class="badge bg-success fs-5"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancel_button"
                        data-bs-dismiss="modal">キャンセル</button>
                    <button type="button" class="btn btn-primary" id="confirm_button">更新</button>
                </div>
            </div>
        </div>
    </div>
    <script src="/common/newModal.js"></script>
    <script>
        function showCustomAlert(message) {
            const alertBox = document.getElementById('customAlert');
            alertBox.innerText = message;
            alertBox.style.display = 'block';

            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 3000); // 3秒後に自動的に消える
        }
    </script>
</body>

</html>