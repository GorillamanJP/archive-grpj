<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
session_start();
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";

if(!isset($_POST["id"]) || $_POST["id"] === ""){
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
    <title>入荷処理</title>
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
    <h1 class="text-center mt-3 my-3">入荷処理</h1>
    <div class="container">
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <form id="updateForm" action="update.php" method="post"
            onsubmit="event.preventDefault(); checkAndSubmitForm();">
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
                <button type="submit" class="btn btn-outline-primary">更新</button>
                <a href="../../list/" class="btn btn-outline-secondary">戻る</a>
            </div>
        </form>
    </div>
    <!-- 更新確認モーダル -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">更新の確認</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <button type="button" class="btn btn-primary" id="confirmUpdateBtnModal">更新</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function updateNewStock() {
            const currentStock = parseInt(document.getElementById('currentStock').textContent);
            const addQuantity = parseInt(document.getElementById('add_quantity').value);
            const newStock = currentStock + addQuantity;

            document.getElementById('confirmCurrentStock').textContent = currentStock;
            document.getElementById('confirmNewStock').textContent = newStock;
        }

        document.getElementById('add_quantity').addEventListener('input', updateNewStock);

        document.getElementById('confirmUpdateBtnModal').addEventListener('click', function () {
            document.getElementById('updateForm').submit();
        });

        function checkAndSubmitForm() {
            const addQuantity = parseInt(document.getElementById('add_quantity').value);

            if (isNaN(addQuantity) || addQuantity <= 0) {
                document.getElementById('updateForm').submit();
            } else {
                var myModal = new bootstrap.Modal(document.getElementById('confirmModal'), {
                    keyboard: false
                });
                myModal.show();
            }
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' && event.target.nodeName !== 'TEXTAREA') {
                event.preventDefault();

                console.log('Enterキーが押されました');

                const activeModal = document.querySelector('.modal.show');
                if (activeModal) {
                    if (activeModal.querySelector('#confirmUpdateBtnModal')) {
                        activeModal.querySelector('#confirmUpdateBtnModal').click();
                        console.log('confirmUpdateBtnModalがEnterキーでクリックされました');
                    }
                } else {
                    checkAndSubmitForm();
                    console.log('最初の更新ボタンがEnterキーでクリックされました');
                }
            }
        });
    </script>
</body>

</html>