<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レジ/商品管理/商品登録</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <link rel="stylesheet" href="/common/create.css">
    <style>
        .custom-alert {
            position: fixed;
            top: 80px;
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
    <h1 class="text-center my-3">商品登録</h1>
    <div class="container">
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <form id="modal_required_form" action="create.php" method="post" enctype="multipart/form-data"
            onsubmit="return handleSubmit(event);">
            <table class="table table-bordered table-info table-hover">
                <tr class="form-group">
                    <th class="align-middle">商品イメージ</th>
                    <td class="table-secondary">
                        <img id="image_preview" src="#" alt="商品画像プレビュー" style="display:none; width: 200px;">
                    </td>
                </tr>
                <tr class="form-group">
                    <th class="aligin-middle">画像選択</th>
                    <td class="table-secondary">
                        <input type="file" name="item_image" id="item_image" accept="image/jpeg" class="form-control"
                            required>
                    </td>
                </tr>
                <tr class="form-group">
                    <th class="aligin-middle">商品名</th>
                    <td class="table-secondary">
                        <input type="text" name="item_name" id="item_name" class="form-control" required>
                    </td>
                </tr>
                <tr class="form-group">
                    <th class="aligin-middle">価格</th>
                    <td class="table-secondary">
                        <input type="number" name="price" id="price" class="form-control" required>
                    </td>
                </tr>
                <tr class="form-group">
                    <th class="aligin-middle">在庫</th>
                    <td class="table-secondary">
                        <input type="number" name="add_quantity" id="add_quantity" class="form-control" required>
                    </td>
                </tr>
            </table>
            <div class="text-center">
                <button type="submit" id="submit_button" class="btn btn-outline-primary btn-lg">登録</button>
                <a href="../list/" class="btn btn-outline-secondary btn-lg">戻る</a>
            </div>
        </form>
    </div>
    <!-- 新モーダル -->
    <div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalLabel">確認</h1>
                    <button type="button" class="btn-close" aria-label="Close" id="close_button"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body fw-bold fs-4">
                    こちらの内容でよろしいですか？
                </div>
                <div>
                    <table class="table table-borderless">
                        <tr>
                            <th class="fs-5" style="width: 150px; text-align: right;"><strong>商品名:</strong></th>
                            <td class="fs-5" style="text-align: left;"><span id="confirmItemName"></span></td>
                        </tr>
                        <tr>
                            <th class="fs-5" style="width: 150px; text-align: right;"><strong>価格:</strong></th>
                            <td class="fs-5" style="text-align: left;"><span id="confirmPrice"></span></td>
                        </tr>
                        <tr>
                            <th class="fs-5" style="width: 150px; text-align: right;"><strong>在庫:</strong></th>
                            <td class="fs-5" style="text-align: left;"><span id="confirmNewStock"></span></td>
                        </tr>
                        <tr>
                            <th class="fs-5" style="width: 150px; text-align: right;"><strong>商品イメージ:</strong></th>
                            <td class="fs-5" style="text-align: left;"><img id="confirmItemImage" style="width: 200px;">
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancel_button"
                        data-bs-dismiss="modal">キャンセル</button>
                    <button type="button" class="btn btn-primary" id="confirm_button">登録</button>
                </div>
            </div>
        </div>
    </div>
    <script src="/common/newModal.js"></script>
    <script>
        document.getElementById('item_image').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const preview = document.getElementById('image_preview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>