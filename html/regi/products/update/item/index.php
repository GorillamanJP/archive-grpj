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
    <title>商品更新</title>
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
    <h1 class="text-center mt-3 my-3">商品更新</h1>
    <div class="container">
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <table class="table table-bordered table-info table-hover ">
            <form id="modal_required_form" action="update.php" method="post" enctype="multipart/form-data"
                onsubmit="return handleSubmit(event);">
                <input type="hidden" name="id" value="<?= $product->get_item_id() ?>">
                <tr class="form-group">
                    <th class="align-middle">商品名</th>
                    <td class="table-secondary"><input type="text" name="item_name" id="item_name"
                            value="<?= $product->get_item_name() ?>" class="form-control" required></td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">価格</th>
                    <td class="table-secondary"><input type="number" name="price" id="price"
                            value="<?= $product->get_price() ?>" class="form-control" required></td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">商品イメージ</th>
                    <td class="table-secondary"><img src="data:image/jpeg;base64,<?= $product->get_item_image() ?>"
                            alt="商品画像　ID<?= $product->get_item_id() ?>番" id="now_item_image" style="width: 200px;"></td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">画像選択</th>
                    <td class="table-secondary"><input type="file" name="new_item_image" id="new_item_image"
                            accept="image/jpeg" class="form-control" required></td>
                </tr>
        </table>
        <div class="text-center">
            <input type="submit" class="btn btn-outline-primary btn-lg" id="initialUpdateBtn" value="更新">
            <a href="../../list/" class="btn btn-outline-secondary btn-lg">戻る</a>
        </div>
        </form>
    </div>
    <!-- 更新確認モーダル -->
    <div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">更新の確認</h5>
                    <button type="button" class="btn-close" aria-label="Close" id="close_button"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold fs-4">こちらの内容で更新しますか？</p>
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
                                <th class="fs-5" style="width: 150px; text-align: right;"><strong>商品イメージ:</strong></th>
                                <td class="fs-5" style="text-align: left;"><img id="confirmItemImage"
                                        style="width: 200px;"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancel_button" data-bs-dismiss="modal">キャンセル</button>
                    <button type="button" class="btn btn-primary" id="confirm_button">更新</button>
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
<script src="./set_now_image.js"></script>
</html>