<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
session_start();

$ok = true;
$message = "";

if (!isset($_POST["product_id"]) || $_POST["product_id"] === "") {
    $message .= "「商品」";
    $ok = false;
}

if (!isset($_POST["quantity"]) || $_POST["quantity"] === "") {
    $message .= "「購入数」";
    $ok = false;
}

if (!$ok) {
    $message .= "の項目が空になっています。";
    $_SESSION["message"] = $message;
    $_SESSION["message_type"] = "danger";
    session_write_close();
    header("Location: /regi/");
    exit();
}

$product_ids = $_POST["product_id"];
$quantities = $_POST["quantity"];

$total_price = 0;
$total_amount = 0;

require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/stocks/stock.php";
// 在庫チェック
try {
    for ($i = 0; $i < count($quantities); $i++) {
        $stock = new Stock();
        $stock = $stock->get_from_id($product_ids[$i]);
        $after_quantity = $stock->get_quantity() - $quantities[$i];
        if ($after_quantity < 0) {
            $_SESSION["message"] = "購入数に対し在庫が不足するため、購入処理ができませんでした。";
            $_SESSION["message_type"] = "danger";
            session_write_close();
            header("Location: /regi/");
            exit();
        }
    }
}catch(\Throwable $e){
    $_SESSION["message"] = "商品が見つかりませんでした。";
    $_SESSION["message_details"] = "選ばれた商品が削除された可能性があります。";
    $_SESSION["message_type"] = "danger";
    session_write_close();
    header("Location: /regi/");
    exit();
}

// ファイルロックを使って決済画面は一つの端末で一つのタブしかアクセスできない状態を作り出す
$lockfile_path = "/tmp/sales_create.lock";
$lockfile = fopen($lockfile_path, "c+");

// ファイルロックを取得
if (flock($lockfile, LOCK_EX)) {
    // ファイルポインタをリセット
    fseek($lockfile, 0);
    $status = fread($lockfile, 1);

    if ($status == "0" || $status == "") {
        // ロック可能
        unset($_SESSION["product_id"]);
        unset($_SESSION["quantity"]);
        // ロックをかける
        ftruncate($lockfile, 0);
        fwrite($lockfile, "1");
        fflush($lockfile);
        flock($lockfile, LOCK_UN);
        fclose($lockfile);
    } else {
        // ロック失敗(すでにロック済み)
        $_SESSION["product_id"] = $_POST["product_id"];
        $_SESSION["quantity"] = $_POST["quantity"];
        session_write_close();
        fclose($lockfile);
        header("Location: ./wait.php");
        exit();
    }
} else {
    // ロック取得失敗
    fclose($lockfile);
    $_SESSION["message"] = "決済処理の準備に失敗しました。";
    $_SESSION["message_details"] = "ロックの取得に失敗しました。";
    $_SESSION["message_type"] = "danger";
    header("Location: /regi/");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/items/item.php";
?>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お支払い</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/common/create.css">
    <style>
        .form-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .form-section {
            flex: 1 1 100%;
        }

        .input-number {
            width: 100px;
        }
    </style>

</head>

<body>
    <h1 class="text-center my-3">お支払い</h1>
    <form action="./create.php" method="post" id="form" onsubmit="return check_received_price()">
        <div class="container">
            <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
            <h2>会計詳細</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>商品名</th>
                        <th>価格</th>
                        <th>購入数</th>
                        <th>小計</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($product_ids); $i++): ?>
                        <?php
                        $item = new Item();
                        $item = $item->get_from_id($product_ids[$i]);

                        $item_name = $item->get_item_name();
                        $price = $item->get_price();
                        $quantity = $quantities[$i];
                        $subtotal = $price * $quantity;
                        $total_price += $subtotal;
                        $total_amount += $quantity;
                        ?>
                        <tr>
                            <input type="hidden" name="product_id[]" value="<?= $item->get_id() ?>" required>
                            <td>
                                <span><?= $item_name ?></span>
                                <input type="hidden" name="product_name[]" value="<?= $item_name ?>" required>
                            </td>
                            <td>
                                <span><?= $price ?></span>
                                <input type="hidden" name="product_price[]" value="<?= $price ?>" required>
                            </td>
                            <td>
                                <span><?= $quantity ?></span>
                                <input type="hidden" name="quantity[]" value="<?= $quantity ?>" required>
                            </td>
                            <td>
                                <span><?= $subtotal ?></span>
                                <input type="hidden" name="subtotal[]" value="<?= $subtotal ?>" required>
                            </td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
            <table class="table mt-3">
                <tr>
                    <th>合計購入数</th>
                    <td><span id="total_amount_disp"><?= $total_amount ?></span>個</td>
                    <input type="hidden" name="total_amount" value="<?= $total_amount ?>" required>
                </tr>
                <tr>
                    <th>合計金額</th>
                    <td><span id="total_price_disp"><?= $total_price ?></span>円</td>
                    <input type="hidden" name="total_price" id="total_price" value="<?= $total_price ?>" required>
                </tr>
                <tr>
                    <th>お預かり</th>
                    <td><input type="number" name="received_price_disp" id="received_price_disp" required>円</td>
                    <input type="hidden" name="received_price" id="received_price" value="0" required>
                </tr>
                <tr>
                    <th>お釣り</th>
                    <td><span id="returned_price_disp">0</span>円</td>
                    <input type="hidden" name="returned_price" id="returned_price" value="0" required>
                </tr>
            </table>
            <div class="text-center mt-4">
                <input type="submit" value="購入確定" class="btn btn-primary btn-lg round-button">
            </div>
        </div>
    </form>

    <script>
        // お釣り計算周りの処理
        function calc_and_disp_transaction() {
            document.getElementById("received_price").value = document.getElementById("received_price_disp").value;

            const input = document.getElementById("received_price").value;
            const returned_price_value = input - document.getElementById("total_price").value;
            document.getElementById("returned_price").value = returned_price_value;
            document.getElementById("returned_price_disp").innerText = returned_price_value;
        }
        calc_and_disp_transaction();
        document.getElementById("received_price_disp").addEventListener("input", calc_and_disp_transaction);

        // お預かり金額が少なくないかチェック
        function check_received_price() {
            const received_price = document.getElementById("returned_price").value;
            if (received_price < 0) {
                alert("お預かり金額が不足しています。");
                return false;
            } else {
                if (received_price > 0) {
                    let stat = confirm("おつり" + received_price + "円を渡してください。");
                    if (stat == false) {
                        return false;
                    }
                }
                return confirm("支払いを確定します。よろしいですか？");
            }
        }

        // ロック解除スクリプト
        window.addEventListener("beforeunload", function () {
            navigator.sendBeacon("./unlock.php");
        });
    </script>
</body>

</html>