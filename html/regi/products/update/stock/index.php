<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
$id = htmlspecialchars($_POST["id"]);
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/products/product.php";

$product = new Product();
$product = $product->get_from_stock_id($id);
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
        <form action="update.php" method="post">
            <table class="table table-bordered table-info table-hover">
                <tr class="form-group">
                    <th class="align-middle">商品イメージ</th>
                    <td class="table-secondary"><img
                            src="data:image/jpeg;base64,<?= $product->get_item()->get_item_image() ?>"
                            alt="商品画像　ID<?= $product->get_item()->get_id() ?>番" id="now_item_image"
                            style="width: 200px;"></td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">商品名</th>
                    <td class="table-secondary"><?= $product->get_item()->get_item_name() ?></td>
                </tr>
                <tr class="form-group">
                    <th>現在の在庫数</th>
                    <td class="table-secondary"><?= $product->get_stock()->get_quantity() ?></td>
                </tr>
                <tr class="form-group">
                    <th class="align-middle">入荷数</th>
                    <td class="table-secondary"><input type="number" name="add_quantity" id="add_quantity"
                            class="form-control"></td>
                </tr>
            </table>
            <input type="hidden" name="id" value="<?= $product->get_stock()->get_id() ?>"></p>
            <div class="text-center">
                <input type="submit" value="更新" class="btn btn-outline-primary">
                <a href="../../list/index.php" class="btn btn-outline-secondary">戻る</a>
            </div>
        </form>
    </div>
</body>

</html>