<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/protects/protect.php";
?>
<?php
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
    $_SESSION["message"] = $message;
    $_SESSION["message_type"] = "danger";
    session_write_close();
    header("Location: /order/");
    exit();
}

$product_ids = $_POST["product_id"];
$quantities = $_POST["quantity"];

$total_price = 0;
$total_amount = 0;

$order_items = [];

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/products/product.php";
// 在庫チェックしつつ購入内容のデータを組み立てる
try {
    for ($i = 0; $i < count($product_ids); $i++) {
        $product = new Product();
        $product = $product->get_from_item_id($product_ids[$i]);
        $item = $product->get_item();
        $stock = $product->get_stock();
        $order_quantity = $quantities[$i];
        $after_stock = $stock->get_quantity() - $order_quantity;
        if ($after_stock < 0) {
            $_SESSION["message"] = "購入数に対し在庫が不足するため、購入処理ができませんでした。";
            $_SESSION["message_type"] = "danger";
            session_write_close();
            header("Location: /order/");
            exit();
        }
        $id = $item->get_id();
        $name = $item->get_item_name();
        $price = $item->get_price();
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
    $_SESSION["message"] = "商品が見つかりませんでした。";
    $_SESSION["message_details"] = "選ばれた商品が削除された可能性があります。";
    $_SESSION["message_type"] = "danger";
    session_write_close();
    header("Location: /order/");
    exit();
}

if ($total_price < 0) {
    $_SESSION["message"] = "合計金額が0円以下になります。";
    $_SESSION["message_details"] = "選んだ商品を確認してください。クーポンなどの割引商品を選びすぎた可能性があります。";
    $_SESSION["message_type"] = "danger";
    session_write_close();
    header("Location: /order/");
    exit();
}

unset($_SESSION["order"]["data"]);
foreach($order_items as $item){
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
    <title>注文確認</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

</head>

<body>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
    <h1>注文確認</h1>
    <h2>注文内容は以下の通りでよろしいですか？</h2>
    <p id="back_message">確定しない場合は、<span id="back_second">30</span>秒後に前のページに戻ります。</p>
    <h2>会計詳細</h2>
    <form action="./create.php" method="post">
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
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td>
                            <span><?= $item["name"] ?></span>
                        </td>
                        <td>
                            <span><?= $item["price"] ?></span>
                        </td>
                        <td>
                            <span><?= $item["order_quantity"] ?></span>
                        </td>
                        <td>
                            <span><?= $item["subtotal"] ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <table class="table mt-3">
            <tr>
                <th>合計購入数</th>
                <td><span id="total_amount_disp"><?= $total_amount ?></span>個</td>
            </tr>
            <tr>
                <th>合計金額</th>
                <td><span id="total_price_disp"><?= $total_price ?></span>円</td>
            </tr>
        </table>
        <div class="text-center mt-4">
            <p><input type="submit" value="注文確定" class="btn btn-primary btn-lg round-button"></p>
            <p><a href="/order/"><button type="button" class="btn btn-secondary btn-lg round-button">戻る</button></a>
            </p>
        </div>
    </form>
</body>
<script>
    setInterval(() => {
        const second = document.getElementById("back_second");
        second.innerText -= 1;
        if(second.innerText <= 0){
            document.getElementById("back_message").innerHTML = "前のページに戻っています…";
            location.href = "/order/";
        }
    }, 1000);
</script>
</html>