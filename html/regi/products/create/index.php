<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品登録</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/common/create.css">
</head>

<body>
    <h1 class="text-center my-3">商品登録</h1>
    <div class="container">
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <table class="table table-bordered table-info table-hover ">
            <form id="registerForm" action="create.php" method="post" enctype="multipart/form-data"
                onsubmit="event.preventDefault(); showConfirmationModal();">
                <tr class="form-group">
                    <th class="aligin-middle">商品名</th>
                    <td class="table-secondary"><input type="text" name="item_name" id="item_name" class="form-control"
                            required></td>
                </tr>
                <tr class="form-group">
                    <th class="aligin-middle">価格</th>
                    <td class="table-secondary"><input type="number" name="price" id="price" class="form-control"
                            required></td>
                </tr>
                <tr class="form-group">
                    <th class="aligin-middle">在庫</th>
                    <td class="table-secondary"><input type="number" name="add_quantity" id="add_quantity"
                            class="form-control" required></td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">商品イメージ</th>
                    <td class="table-secondary"><img id="image_preview" src="#" alt="商品画像プレビュー"
                            style="display:none; width: 200px;"></td>
                </tr>
                <tr class="form-group">
                    <th class="aligin-middle">画像選択</th>
                    <td class="table-secondary"><input type="file" name="item_image" id="item_image" accept="image/jpeg"
                            class="form-control" required></td>
                </tr>
        </table>
        <div class="text-center">
            <input type="submit" class="btn btn-outline-primary btn-lg" id="initialRegisterBtn" value="登録">
            <a href="../list/" class="btn btn-outline-secondary btn-lg">戻る</a>
        </div>
        </form>
    </div>
    <!-- 登録確認モーダル -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">登録の確認</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold fs-4">本当に登録しますか？</p>
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
                                <td class="fs-5" style="text-align: left;"><span id="confirmQuantity"></span></td>
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
                    <button type="button" class="btn btn-primary" id="confirmRegisterBtn">登録</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function showConfirmationModal() {
            document.getElementById('confirmItemName').textContent = document.getElementById('item_name').value;
            document.getElementById('confirmPrice').textContent = document.getElementById('price').value;
            document.getElementById('confirmQuantity').textContent = document.getElementById('add_quantity').value;
            const newImage = document.getElementById('item_image').files[0];
            if (newImage) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('confirmItemImage').src = e.target.result;
                };
                reader.readAsDataURL(newImage);
            } else {
                document.getElementById('confirmItemImage').src = document.getElementById('image_preview').src;
            }
            var myModal = new bootstrap.Modal(document.getElementById('confirmModal'), {
                keyboard: false
            });
            myModal.show();
        }

        document.getElementById('initialRegisterBtn').addEventListener('click', function () {
            showConfirmationModal();
        });

        document.getElementById('confirmRegisterBtn').addEventListener('click', function () {
            document.getElementById('registerForm').submit();
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' && event.target.nodeName !== 'TEXTAREA') {
                event.preventDefault();
                console.log('Enterキーが押されました');
                const activeModal = document.querySelector('.modal.show');
                if (activeModal) {
                    if (activeModal.querySelector('#confirmRegisterBtn')) {
                        activeModal.querySelector('#confirmRegisterBtn').click();
                        console.log('confirmRegisterBtnがEnterキーでクリックされました');
                    }
                } else {
                    showConfirmationModal();
                    console.log('initialRegisterBtnがEnterキーでクリックされました');
                }
            }
        });

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