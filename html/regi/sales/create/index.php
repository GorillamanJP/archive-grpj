<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";

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
    redirect_with_error("/regi/", $message, "", "warning");
}

$product_ids = $_POST["product_id"];
$quantities = $_POST["quantity"];

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/temp_purchase_details/temp_purchase_detail.php";
$temp_purchase_detail = new Temp_Purchases_Detail();

$total_price = 0;
$total_amount = 0;

$buy_items = [];

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/products/product.php";
// 在庫チェックしつつ購入内容のデータを組み立てる
try {
    for ($i = 0; $i < count($product_ids); $i++) {
        $pid = htmlspecialchars($product_ids[$i]);
        $qts = htmlspecialchars($quantities[$i]);

        $product = new Product();
        $product = $product->get_from_item_id($pid);
        if ($product->get_delete_flag() == true) {
            throw new Exception("指定した商品は削除されました。", 0);
        }
        if ($product->get_buy_available_count() < 0) {
            throw new Exception("残り注文可能数が0未満のため、購入処理ができませんでした。", 0);
        }
        $stock_left = $product->get_now_stock();
        $buy_quantity = $qts;
        $after_stock = $stock_left - $buy_quantity;
        if ($after_stock - $temp_purchase_detail->get_exists_temp_quantity_from_item_id($product->get_item_id()) < 0) {
            throw new Exception("購入数に対し在庫が不足するため、購入処理ができませんでした。誰かが会計中の場合にも、このエラーが出る場合があります。", 0);
        }
        
        $id = $product->get_item_id();
        $name = $product->get_item_name();
        $price = $product->get_price();
        $subtotal = $buy_quantity * $price;
        $buy_items[] = [
            "id" => $id,
            "name" => $name,
            "price" => $price,
            "buy_quantity" => $buy_quantity,
            "subtotal" => $subtotal,
        ];
        $total_price += $subtotal;
        $total_amount += $buy_quantity;
    }
} catch (Throwable $th) {
    redirect_with_error("/regi/", "エラーが発生しました。", $th->getMessage(), "danger");
}

if ($total_price < 0) {
    redirect_with_error("/regi/", "合計金額が０円以下になります。", "選んだ商品を確認してください。クーポンなどの割引商品を選びすぎた可能性があります。", "danger");
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/verify_int_value.php";
if (verify_int_value($total_price, $total_amount) == false) {
    redirect_with_error("/regi/", "数値エラー", "購入数または合計金額が最大値を超えている可能性があります。", "danger");
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/purchases/purchase.php";
try {
    $temp_purchase = new Purchases();
    $temp_purchase = $temp_purchase->create($product_ids, $quantities);
    $_SESSION["temp_purchase"]["id"] = $temp_purchase->get_temp_purchases()->get_id();
} catch (Throwable $th) {
    redirect_with_error("/regi/", "エラーが発生しました。", $th->getMessage(), "danger");
}

unset($_SESSION["regi"]["data"]);
foreach ($buy_items as $item) {
    $_SESSION["regi"]["data"]["product_id"][] = $item['id'];
    $_SESSION["regi"]["data"]["product_name"][] = $item['name'];
    $_SESSION["regi"]["data"]["product_price"][] = $item['price'];
    $_SESSION["regi"]["data"]["quantity"][] = $item['buy_quantity'];
    $_SESSION["regi"]["data"]["subtotal"][] = $item['subtotal'];
}
$_SESSION["regi"]["data"]["total_amount"] = $total_amount;
$_SESSION["regi"]["data"]["total_price"] = $total_price;
?>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レジ/お支払い</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <link rel="stylesheet" href="/common/create.css">
    <style>
        body {
            background-color: #e6e6fa;
            /* 淡いラベンダー */
        }

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
    <form action="./create.php" method="post" id="form">
        <div class="container">
            <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;"
                id="keep_alive">
                <p class="m-0">
                    <span id="keep_alive_message">レジとの接続が維持できませんでした。維持できない状態が続く場合、前の画面に戻ります。</span>
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
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
                    <?php foreach ($buy_items as $item): ?>
                        <tr>
                            <td>
                                <span class="item_name"><?= $item["name"] ?></span>
                            </td>
                            <td>
                                <span class="item_price"><?= $item["price"] ?></span>
                            </td>
                            <td>
                                <span class="item_buy_quantity"><?= $item["buy_quantity"] ?></span>
                            </td>
                            <td>
                                <span class="item_subtotal"><?= $item["subtotal"] ?></span>
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
                <input type="hidden" name="checksum" id="checksum" value="0">
                <p><input type="submit" value="購入確定" class="btn btn-primary btn-lg round-button"></p>
                <?php
                // モバイルオーダー受け取りの場合戻る先が違うので判断
                $back_url = "/regi/";
                if (isset($_SESSION["regi"]["order"]["id"])) {
                    $back_url = "/regi/order/list/";
                }
                ?>
                <p>
                    <a href="<?= $back_url ?>">
                        <button type="button" class="btn btn-secondary btn-lg round-button">戻る</button>
                    </a>
                </p>
            </div>
        </div>
    </form>

    <!-- 支払金額不足モーダル -->
    <div class="modal fade" id="insufficientFundsModal" tabindex="-1" aria-labelledby="insufficientFundsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="insufficientFundsModalLabel">支払金額不足</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold fs-4">お預かり金額が不足しています。</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 購入確定モーダル -->
    <div class="modal fade" id="confirmPurchaseModal" tabindex="-1" aria-labelledby="confirmPurchaseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmPurchaseModalLabel">購入の確認</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold fs-4">本当に購入を確定しますか？</p>
                    <div>
                        <table class="table table-borderless">
                            <tr>
                                <th class="fs-5" style="width: 150px; text-align: right;"><strong>合計金額:</strong></th>
                                <td class="fs-5" style="text-align: left;"><span id="confirmTotalPrice"></span>円</td>
                            </tr>
                            <tr>
                                <th class="fs-5" style="width: 150px; text-align: right;"><strong>お預かり:</strong></th>
                                <td class="fs-5" style="text-align: left;"><span id="confirmReceivedPrice"></span>円</td>
                            </tr>
                            <tr>
                                <th class="fs-5" style="width: 150px; text-align: right;"><strong>お釣り:</strong></th>
                                <td class="fs-5" style="text-align: left;"><span id="confirmReturnedPrice"></span>円</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <button type="button" class="btn btn-primary" id="confirmPurchaseBtn">購入確定</button>
                </div>
            </div>
        </div>
    </div>

    <!-- お釣り確認モーダル -->
    <div class="modal fade" id="confirmChangeModal" tabindex="-1" aria-labelledby="confirmChangeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmChangeModalLabel">お釣りの確認</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold fs-4">お釣り <span id="confirmChangeAmount"></span> 円を渡してください。</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelChangeBtn"
                        data-bs-dismiss="modal">キャンセル</button>
                    <button type="button" class="btn btn-primary" id="confirmChangeBtn">確認</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 購入確定ボタンにイベントリスナーを追加 -->
    <script>
        let submitted = false;
        let confirmPurchaseProcessing = false;
        let confirmChangeProcessing = false;
        let modalQueue = [];
        let enterPressTimeout;
        let lastEnterPressTime = 0;

        document.getElementById('form').addEventListener('submit', function (event) {
            event.preventDefault();
            if (submitted) return;
            submitted = true;

            console.log('フォームが送信されました');

            calc_and_disp_transaction();

            const receivedPrice = document.getElementById('received_price').value;
            const returnedPrice = document.getElementById('returned_price').value;

            if (returnedPrice < 0) {
                console.log('不足金額モーダルを表示');
                showModal('insufficientFundsModal');
            } else {
                console.log('購入確認モーダルを表示');
                document.getElementById('confirmTotalPrice').textContent = document.getElementById('total_price_disp').textContent;
                document.getElementById('confirmReceivedPrice').textContent = document.getElementById('received_price_disp').value;
                document.getElementById('confirmReturnedPrice').textContent = document.getElementById('returned_price_disp').textContent;
                showModal('confirmPurchaseModal');
            }

            setTimeout(() => submitted = false, 1000);
        });

        function showModal(modalId) {
            const modal = new bootstrap.Modal(document.getElementById(modalId), { keyboard: false });
            modal.show();

            calc_and_disp_transaction();

            console.log(`${modalId}が表示されました`);

            document.getElementById(modalId).addEventListener('shown.bs.modal', function () {
                if (modalId === 'confirmPurchaseModal') {
                    confirmPurchaseProcessing = false;
                    console.log('confirmPurchaseProcessingがリセットされました');
                } else if (modalId === 'confirmChangeModal') {
                    confirmChangeProcessing = false;
                    console.log('confirmChangeProcessingがリセットされました');
                }
            });

            document.getElementById(modalId).addEventListener('hidden.bs.modal', function () {
                if (modalQueue.length > 0) {
                    showModal(modalQueue.shift());
                }
                console.log(`${modalId}が隠されました`);
            }, { once: true });
        }

        document.getElementById('confirmPurchaseBtn').addEventListener('click', function () {
            processConfirmPurchase();
        });

        document.getElementById('confirmChangeBtn').addEventListener('click', function () {
            processConfirmChange();
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' && event.target.nodeName !== 'TEXTAREA') {
                event.preventDefault();

                const now = new Date().getTime();
                if (now - lastEnterPressTime < 500) return;
                lastEnterPressTime = now;

                console.log('Enterキーが押されました');

                clearTimeout(enterPressTimeout);

                const activeModal = document.querySelector('.modal.show');
                if (activeModal) {
                    if (activeModal.querySelector('#confirmPurchaseBtn') && !confirmPurchaseProcessing) {
                        processConfirmPurchase();
                    } else if (activeModal.querySelector('#confirmChangeBtn') && !confirmChangeProcessing) {
                        processConfirmChange();
                    }

                    enterPressTimeout = setTimeout(() => {
                        confirmPurchaseProcessing = false;
                        confirmChangeProcessing = false;
                    }, 1000);
                } else {
                    calc_and_disp_transaction();
                    console.log('フォームが送信されます');
                    document.getElementById('form').dispatchEvent(new Event('submit'));
                }
            }
        });

        function processConfirmPurchase() {
            if (confirmPurchaseProcessing) return;
            confirmPurchaseProcessing = true;

            const returnedPrice = document.getElementById('returned_price').value;
            const confirmPurchaseModal = bootstrap.Modal.getInstance(document.getElementById('confirmPurchaseModal'));
            confirmPurchaseModal.hide();

            console.log('confirmPurchaseBtnがクリックされました');
            console.log(`returnedPrice: ${returnedPrice}`);

            if (returnedPrice > 0) {
                document.getElementById('confirmChangeAmount').textContent = returnedPrice;
                showModal('confirmChangeModal', () => {
                    console.log('confirmChangeModalが表示されました');
                });
            } else {
                calc_and_disp_transaction();
                console.log('フォームが送信されます');
                document.getElementById('form').submit();
            }
        }

        function processConfirmChange() {
            if (confirmChangeProcessing) return;
            confirmChangeProcessing = true;

            console.log('confirmChangeBtnがクリックされました');
            console.log('フォーム送信の準備中...');

            calc_and_disp_transaction();

            console.log('フォームが送信されます');
            document.getElementById('form').submit();

            setTimeout(() => {
                console.log('confirmChangeProcessingをリセット');
                confirmChangeProcessing = false;
            }, 1000);
        }

        const cancelPurchaseBtn = document.getElementById('cancelPurchaseBtn');
        if (cancelPurchaseBtn) {
            cancelPurchaseBtn.addEventListener('click', function () {
                const confirmPurchaseModal = bootstrap.Modal.getInstance(document.getElementById('confirmPurchaseModal'));
                confirmPurchaseModal.hide();
                confirmPurchaseProcessing = false;
                submitted = false;
                console.log('cancelPurchaseBtnがクリックされました');
            });
        }

        const cancelChangeBtn = document.getElementById('cancelChangeBtn');
        if (cancelChangeBtn) {
            cancelChangeBtn.addEventListener('click', function () {
                const confirmChangeModal = bootstrap.Modal.getInstance(document.getElementById('confirmChangeModal'));
                confirmChangeModal.hide();
                confirmChangeProcessing = false;
                submitted = false;
                console.log('cancelChangeBtnがクリックされました');
            });
        }

        // お釣り計算周りの処理
        function calc_and_disp_transaction() {
            document.getElementById("received_price").value = document.getElementById("received_price_disp").value;
            const input = document.getElementById("received_price").value;
            const returned_price_value = input - document.getElementById("total_price_disp").innerText;
            document.getElementById("returned_price").value = returned_price_value;
            document.getElementById("returned_price_disp").innerText = returned_price_value;
            calc_checksum();
        }

        // チェックサム計算
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
            checksum_txt += document.getElementById("received_price").value;
            checksum_txt += document.getElementById("returned_price").value;

            const encoder = new TextEncoder();
            const data = encoder.encode(checksum_txt);
            const hash_buffer = await crypto.subtle.digest("SHA-256", data);
            const hash_array = Array.from(new Uint8Array(hash_buffer));
            const checksum = hash_array.map(b => b.toString(16).padStart(2, "0")).join("");
            document.getElementById("checksum").value = checksum;
        }

        document.getElementById("received_price_disp").addEventListener("input", calc_and_disp_transaction);

        // keep_alive処理
        let timeoutOccurred = false;

        function sendKeepAlive() {
            const controller = new AbortController();
            const signal = controller.signal;
            fetch('./keep_alive.php', { signal })
                .then(response => response.text())
                .then(data => {
                    if (data === "keepalive success") {
                        timeoutOccurred = false;
                        document.getElementById("keep_alive").style = "display: none;";
                    } else {
                        handleTimeout();
                    }
                })
                .catch(error => {
                    handleTimeout();
                });
            setTimeout(() => controller.abort(), 10000);
        }

        setInterval(sendKeepAlive, 5000);

        function handleTimeout() {
            if (!timeoutOccurred) {
                timeoutOccurred = true;
                document.getElementById("keep_alive").style = "";
                setTimeout(function () {
                    if (timeoutOccurred) {
                        window.location.href = "/regi/";
                    }
                }, 25000);
            }
        }
    </script>

</body>

</html>