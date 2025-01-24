<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>注文待ち画面</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
        }

        .flex {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 93vh;
            margin: 0;
        }

        .container {
            display: flex;
            width: 100%;
            height: 100%;
            border: 2px solid #333;
        }

        .column {
            width: 50%;
            padding: 20px;
            box-sizing: border-box;
        }

        .column.left {
            background-color: lightblue;
        }

        .column.right {
            background-color: lightgreen;
        }

        .order-number {
            font-size: 4em;
            margin: 10px 0;
        }

        .header {
            font-size: 3em;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/common/alert.php"; ?>
    <h3 class="text-center">モバイルオーダー</h3>
    <div class="flex">
        <div class="container container-fluid p-0">
            <div class="column left">
                <div class="header text-center">注文待ち</div>
                <div id="wait-numbers">
                    <!-- 追加の注文番号をここに挿入 -->
                </div>
            </div>
            <div class="column right">
                <div class="header text-center">呼び出し中</div>
                <div id="call-numbers">
                    <!-- 追加の呼び出し中の番号をここに挿入 -->
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    // 定期的にデータ取得
    async function check_order_status() {
        const resp = await fetch("./get_order_status.php");
        try {
            if (resp.ok) {
                const data = await resp.json();
                const wait = data.wait;
                const call = data.call;

                const leftColumn = document.querySelector("#wait-numbers");
                const rightColumn = document.querySelector("#call-numbers");

                leftColumn.innerHTML = "";
                rightColumn.innerHTML = "";

                wait.forEach(function (orderNumber) {
                    const orderNumberElement = document.createElement("div");
                    orderNumberElement.classList.add("order-number");
                    orderNumberElement.textContent = "注文番号: " + orderNumber;
                    orderNumberElement.classList.add("order-number");
                    leftColumn.appendChild(orderNumberElement);
                });

                call.forEach(function (orderNumber) {
                    const orderNumberElement = document.createElement("div");
                    orderNumberElement.classList.add("order-number");
                    orderNumberElement.textContent = "注文番号: " + orderNumber;
                    orderNumberElement.classList.add("order-number");
                    rightColumn.appendChild(orderNumberElement);
                });
            } else {
                console.error("データ取得に失敗しました。");
            }
        } catch (e) {
            console.error(e);
        }
    }
    setInterval(check_order_status, 5000); // 5秒ごとに更新する


    // 最初に実行する
    check_order_status();
    // ここまで
</script>

</html>