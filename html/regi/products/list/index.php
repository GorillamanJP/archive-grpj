<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php session_start(); ?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/products/product.php";
$product_obj = new Product();
$products = $product_obj->get_all();

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f8f9fa;
            /* 背景色を変更 */
        }

        h1 {
            font-weight: bold;
            color: #333;
        }

        .table {
            border-radius: 0.5em;
            overflow: hidden;
            box-shadow: 0 0.3em 0.6em rgba(0, 0, 0, 0.1);
        }

        .round-button{
            border-radius: 2em;
        }

        .btn-lg-custom {
            padding: 1em 2em;
            font-size: 1.2em;
        }

        td {
            font-size: 1.3em;
            /* 文字サイズを1.3倍にする */
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mt-3">商品一覧</h1>
        <p class="text-center my-3" style="font-size: 1.2em;">最終更新時刻:<span
                id="last-update"><?= date("Y/m/d H:i:s") ?></span></p>
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <div class="text-center my-3">
            <a href="../create/" class="btn btn-outline-primary btn-lg-custom p-2 mx-1">商品登録</a>
            <a href="../../" class="btn btn-outline-success btn-lg-custom p-2 mx-1">レジ画面へ</a>
        </div>
        <?php if (is_null($products)): ?>
            <h2 class="text-center">商品はありません。</h2>
            <p class="text-center"><a href="../create/">新たに商品を登録しましょう！</a></p>
        <?php else: ?>
            <div class="table-responsive my-4">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-info">
                        <tr>
                            <th>商品イメージ</th>
                            <th>商品名</th>
                            <th>価格</th>
                            <th>在庫数</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        <? foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <img src="data:image/jpeg;base64,<?= $product->get_item()->get_item_image() ?>"
                                        alt="商品画像　ID<?= $product->get_item()->get_id() ?>番" class="img-fluid img-thumbnail">
                                </td>
                                <td><?= $product->get_item()->get_item_name() ?></td>
                                <td><?= $product->get_item()->get_price() ?></td>
                                <td><?= $product->get_stock()->get_quantity() ?></td>
                                <td>
                                    <table class="container">
                                        <tr>
                                            <td>
                                                <form action="../update/item/" method="post">
                                                    <input type="hidden" name="id" id="id"
                                                        value="<?= $product->get_item()->get_id() ?>">
                                                    <input type="submit" value="更新" class="btn btn-outline-primary round-button">
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <form action="../update/stock/" method="post">
                                                    <input type="hidden" name="id" id="id"
                                                        value="<?= $product->get_stock()->get_id() ?>">
                                                    <input type="submit" value="入荷" btn class="btn btn-outline-success round-button">
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <!-- 削除ボタン -->
                                                <button type="button" class="btn btn-outline-danger round-button" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    data-id="<?= $product->get_item()->get_id() ?>">
                                                    削除
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
    </div>

    <!-- 削除確認モーダル -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">削除の確認</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                </div>
                <div class="modal-body">
                    本当に削除しますか？
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">削除</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let deleteId;  // 削除する商品のIDを保持する変数

        // モーダルが表示されたときに、削除する商品のIDを設定
        document.getElementById('deleteModal').addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            deleteId = button.getAttribute('data-id');
        });

        // 「削除」ボタンが押されたら、フォームを作成して送信
        document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
            const form = document.createElement('form');
            form.method = 'post';
            form.action = '../delete/';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id';
            input.value = deleteId;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        });
    </script>
    <script src="./check_update.js"></script>
</body>

</html>