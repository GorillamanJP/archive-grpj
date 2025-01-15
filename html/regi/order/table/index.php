<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>注文待ち画面</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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
            font-size: 2em;
            margin: 10px 0;
        }

        .header {
            font-size: 3em;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container container-fluid">
        <div class="column left">
            <div class="header text-center">注文待ち</div>
            <div class="order-number">注文番号: 12345</div>
            <div class="order-number">注文番号: 12346</div>
            <!-- 追加の注文番号をここに挿入 -->
        </div>
        <div class="column right">
            <div class="header text-center">呼び出し中</div>
            <div class="order-number">注文番号: 12340</div>
            <div class="order-number">注文番号: 12341</div>
            <!-- 追加の呼び出し中の番号をここに挿入 -->
        </div>
    </div>
</body>

</html>