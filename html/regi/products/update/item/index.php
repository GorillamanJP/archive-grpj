<?php
require_once $_SERVER['DOCUMENT_ROOT']."/regi/users/login_check.php";
?>
<?php
$id = htmlspecialchars($_POST["id"]);
require_once $_SERVER['DOCUMENT_ROOT']."/../classes/products/product.php";

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
            <form id="updateForm" action="update.php" method="post" enctype="multipart/form-data"
                onsubmit="event.preventDefault(); showConfirmationModal();">
                <input type="hidden" name="id" value="<?= $product->get_item()->get_id() ?>">
                <tr class="form-group">
                    <th class="align-middle">商品名</th>
                    <td class="table-secondary"><input type="text" name="item_name" id="item_name"
                            value="<?= $product->get_item()->get_item_name() ?>" class="form-control" required></td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">価格</th>
                    <td class="table-secondary"><input type="number" name="price" id="price"
                            value="<?= $product->get_item()->get_price() ?>" class="form-control" required></td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">商品イメージ</th>
                    <td class="table-secondary"><img
                            src="data:image/jpeg;base64,<?= $product->get_item()->get_item_image() ?>"
                            alt="商品画像　ID<?= $product->get_item()->get_id() ?>番" id="now_item_image"
                            style="width: 200px;"></td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">画像選択</th>
                    <td class="table-secondary"><input type="file" name="new_item_image" id="new_item_image"
                            accept="image/jpeg" class="form-control" required></td>
                </tr>
        </table>
        <div class="text-center">
            <input type="submit" class="btn btn-outline-primary" value="更新">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <button type="button" class="btn btn-primary" id="confirmUpdateBtn">更新</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function showConfirmationModal() {
            document.getElementById('confirmItemName').textContent = document.getElementById('item_name').value;
            document.getElementById('confirmPrice').textContent = document.getElementById('price').value;
            const newImage = document.getElementById('new_item_image').files[0];
            if (newImage) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('confirmItemImage').src = e.target.result;
                };
                reader.readAsDataURL(newImage);
            } else {
                document.getElementById('confirmItemImage').src = document.getElementById('now_item_image').src;
            }
            var myModal = new bootstrap.Modal(document.getElementById('confirmModal'), {
                keyboard: false
            });
            myModal.show();
        }

        document.getElementById('confirmUpdateBtn').addEventListener('click', function () {
            document.getElementById('updateForm').submit();
        });
    </script>
</body>
<script src="./set_now_image.js"></script>

</html>