<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
if (!isset($_SESSION)) {
    session_start();
}

$ok = true;
if (!isset($_POST["product_id"]) || $_POST["product_id"] === "") {
    $_SESSION["message"] = "商品が選ばれていません。";
    $_SESSION["message_type"] = "danger";
    $ok = false;
}

if (!isset($_POST["quantity"]) || $_POST["quantity"] === "") {
    $_SESSION["message"] = "購入数が0の商品があります。";
    $_SESSION["message_type"] = "danger";
    $ok = false;
}

if (!$ok) {
    header("Location: ../../");
}

$product_ids = $_POST["product_id"];
$quantities = $_POST["quantity"];

$total_price = 0;
$total_amount = 0;

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
</head>

<body>
    <h1>お支払い</h1>
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
    <form action="./create.php" method="post">
        <div class="left-side">
            <h2>会計詳細</h2>
            <table>
                <tr>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>購入数</th>
                    <th>小計</th>
                </tr>
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
                        <input type="hidden" name="product_id[]" value="<?= $item->get_id() ?>">
                        <td>
                            <span><?= $item_name ?></span>
                            <input type="hidden" name="product_name[]" value="<?= $item_name ?>">
                        </td>
                        <td>
                            <span><?= $price ?></span>
                            <input type="hidden" name="product_price[]" value="<?= $price ?>">
                        </td>
                        <td>
                            <span><?= $quantity ?></span>
                            <input type="hidden" name="quantity[]" value="<?= $quantity ?>">
                        </td>
                        <td>
                            <span><?= $subtotal ?></span>
                            <input type="hidden" name="subtotal[]" value="<?= $subtotal ?>">
                        </td>
                    </tr>
                <?php endfor; ?>
            </table>
            <table>
                <tr>
                    <th>合計購入数</th>
                    <td><span id="total_amount"><?= $total_amount ?></span>個</td>
                    <input type="hidden" name="total_amount" value="<?= $total_amount ?>">
                </tr>
                <tr>
                    <th>合計金額</th>
                    <td><span id="total_price"><?= $total_price ?></span>円</td>
                    <input type="hidden" name="total_price" value="<?= $total_price ?>">
                </tr>
                <tr>
                    <th>お預かり</th>
                    <td><span id="received_price_disp">0</span>円</td>
                    <input type="hidden" name="received_price" id="received_price" value="0">
                </tr>
                <tr>
                    <th>お釣り</th>
                    <td><span id="returned_price_disp">0</span>円</td>
                    <input type="hidden" name="returned_price" id="returned_price" value="0">
                </tr>
            </table>
        </div>
        <div class="right-side">
            <table>
                <tr>
                    <td><button type="button" id="numpad_7" class="numpads">7</button></td>
                    <td><button type="button" id="numpad_8" class="numpads">8</button></td>
                    <td><button type="button" id="numpad_9" class="numpads">9</button></td>
                </tr>
                <tr>
                    <td><button type="button" id="numpad_4" class="numpads">4</button></td>
                    <td><button type="button" id="numpad_5" class="numpads">5</button></td>
                    <td><button type="button" id="numpad_6" class="numpads">6</button></td>
                </tr>
                <tr>
                    <td><button type="button" id="numpad_1" class="numpads">1</button></td>
                    <td><button type="button" id="numpad_2" class="numpads">2</button></td>
                    <td><button type="button" id="numpad_3" class="numpads">3</button></td>
                </tr>
                <tr>
                    <td><button type="button" id="numpad_0" class="numpads">0</button></td>
                    <td><button type="button" id="numpad_00" class="numpads">00</button></td>
                    <td id="submit"><input type="submit" value="支払い"></td>
                </tr>
            </table>
        </div>
    </form>

    <script>
        const numpads = document.querySelectorAll(".numpads");
        numpads.forEach(element => {
            element.addEventListener("click", function (event) {
                const received_price_disp = document.getElementById("received_price_disp");
                if (received_price_disp.innerText == "0") {
                    received_price_disp.innerText = "";
                }
                received_price_disp.innerText += element.innerText;
                const received_price_value = parseInt(received_price_disp.innerText);
                document.getElementById("received_price").value = received_price_value;
                const returned_price_disp = document.getElementById("returned_price_disp");
                returned_price_disp.innerText = received_price_value - parseInt(document.getElementById("total_price").innerText);
                document.getElementById("returned_price").value = returned_price_disp.innerText;
            })
        });
    </script>
</body>

</html>