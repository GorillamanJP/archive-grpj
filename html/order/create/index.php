<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/protects/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/is_receive.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";
session_start();
?>
<?php
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
    redirect_with_error("/order/", $message, "", "warning");
}

$product_ids = $_POST["product_id"];
$quantities = $_POST["quantity"];

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/purchases/purchase.php";
try {
    $temp_purchase = new Purchases();
    $temp_purchase = $temp_purchase->create($product_ids, $quantities);
    $_SESSION["temp_purchase"]["id"] = $temp_purchase->get_temp_purchases()->get_id();
} catch (Throwable $th) {
    redirect_with_error("/regi/", "1エラーが発生しました。" . $th->getTraceAsString(), $th->getPrevious()->getPrevious()->getPrevious()->getMessage(), "danger");
}
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/temp_purchase_details/temp_purchase_detail.php";
$temp_purchase_detail = new Temp_Purchases_Detail();

$total_price = 0;
$total_amount = 0;

$order_items = [];

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/products/product.php";
// 在庫チェックしつつ購入内容のデータを組み立てる
try {
    for ($i = 0; $i < count($product_ids); $i++) {
        $product = new Product();
        $product = $product->get_from_item_id($product_ids[$i]);
        $available_left = $product->get_buy_available_count();
        if ($available_left < 0) {
            redirect_with_error("/order/", "在庫が不足しています。", "", "danger");
        }
        if ($quantities[$i] > 10) {
            redirect_with_error("/order/", "購入数過多", "一つの商品につき10個まで注文ができます。それ以上お買い求めいただく場合は、店頭までお越しください。", "warning");
        }
        $order_quantity = $quantities[$i];
        if ($order_quantity < 1) {
            redirect_with_error("/order/", "購入数が1個未満になっています。", "", "danger");
        }
        $after_stock = $available_left - $order_quantity;
        if ($after_stock - $temp_purchase_detail->get_exists_temp_quantity_from_item_id($product->get_item_id()) < 0) {
            redirect_with_error("/order/", "注文数に対し在庫が不足するため、注文処理が出来ませんでした。誰かが注文中の場合も、このエラーが出る場合があります。", "", "danger");
        }
        $id = $product->get_item_id();
        $name = $product->get_item_name();
        $price = $product->get_price();
        $subtotal = $order_quantity * $price;
        $order_items[] = array(
            "id" => $id,
            "name" => $name,
            "price" => $price,
            "order_quantity" => $order_quantity,
            "subtotal" => $subtotal,
        );
        $total_price += $subtotal;
        $total_amount += $order_quantity;
    }
} catch (\Throwable $e) {
    redirect_with_error("/order/", "エラーが発生しました。", $e->getMessage(), "danger");
}

if ($total_price < 0) {
    redirect_with_error("/order/", "合計金額が0円以下になります。", "選んだ商品を確認してください。クーポンなどの割引商品を選びすぎた可能性があります。", "danger");
}

unset($_SESSION["order"]["data"]);
foreach ($order_items as $item) {
    $_SESSION["order"]["data"]["product_id"][] = $item['id'];
    $_SESSION["order"]["data"]["product_name"][] = $item['name'];
    $_SESSION["order"]["data"]["product_price"][] = $item['price'];
    $_SESSION["order"]["data"]["quantity"][] = $item['order_quantity'];
    $_SESSION["order"]["data"]["subtotal"][] = $item['subtotal'];
}
$_SESSION["order"]["data"]["total_amount"] = $total_amount;
$_SESSION["order"]["data"]["total_price"] = $total_price;
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>モバイルオーダー/注文確認</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <style>
        .custom-background {
            background-color: #e3f2fd;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .round-button {
            border-radius: 50px;
        }

        .timer {
            font-size: 1.2em;
            color: #ff6f61;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
        <h1 class="text-center mb-4">注文確認</h1>
        <div class="custom-background">
            <h2 class="text-center mb-4">注文内容は以下の通りでよろしいですか？</h2>
            <p class="text-center" id="back_message">確定しない場合は、<span id="back_second">30</span>秒後に前のページに戻ります。</p>
            <h2 class="text-center mt-4">会計詳細</h2>
            <div class="table-responsive mt-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th>商品名</th>
                            <th>価格</th>
                            <th>購入数</th>
                            <th>小計</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td class="item_name"><?= $item["name"] ?></td>
                                <td class="item_price"><?= $item["price"] ?></td>
                                <td class="item_buy_quantity"><?= $item["order_quantity"] ?></td>
                                <td class="item_subtotal"><?= $item["subtotal"] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive mt-3">
                <table class="table">
                    <tr>
                        <th>合計購入数</th>
                        <td><span id="total_amount_disp"><?= $total_amount ?></span>個</td>
                    </tr>
                    <tr>
                        <th>合計金額</th>
                        <td><span id="total_price_disp"><?= $total_price ?></span>円</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="text-center mt-4">
            <form action="./create.php" method="post">
                <input type="hidden" name="checksum" id="checksum" value="0">
                <p><button type="submit" class="btn btn-outline-primary btn-lg round-button">注文確定</button></p>
                <a href="/order/" class="btn btn-outline-secondary btn-lg round-button">戻る</a>
            </form>
        </div>
    </div>
</body>
<script>
    async function calc_checksum() {
        let checksum_txt = "";
        document.querySelectorAll(".item_name").forEach(element => {
            checksum_txt += element.innerText;
        });
        document.querySelectorAll(".item_price").forEach(element => {
            checksum_txt += element.innerText;
        });
        document.querySelectorAll(".item_buy_quantity").forEach(element => {
            checksum_txt += element.innerText;
        });
        document.querySelectorAll(".item_subtotal").forEach(element => {
            checksum_txt += element.innerText;
        });
        checksum_txt += document.getElementById("total_amount_disp").innerText;
        checksum_txt += document.getElementById("total_price_disp").innerText;

        const encoder = new TextEncoder();
        const data = encoder.encode(checksum_txt);
        const hash_buffer = await crypto.subtle.digest("SHA-256", data);
        const hash_array = Array.from(new Uint8Array(hash_buffer));
        const checksum = hash_array.map(b => b.toString(16).padStart(2, "0")).join("");
        document.getElementById("checksum").value = checksum;
    }

    calc_checksum();

    setInterval(() => {
        const second = document.getElementById("back_second");
        second.innerText -= 1;
        if (second.innerText <= 0) {
            document.getElementById("back_message").innerHTML = "前のページに戻っています…";
            location.href = "/order/";
        }
    }, 1000);
</script>

</html>