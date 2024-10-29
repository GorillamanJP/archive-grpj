<?php
require_once $_SERVER['DOCUMENT_ROOT']."/order/protects/protect.php";
?>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規ユーザー</title>
</head>

<body>
    <h1>初回利用のためのセットアップをしています…</h1>
    <p id="message"></p>
    <script src="/common/get_fingerprint.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let fingerprint = get_fingerprint();
            fetch("./create.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ fingerprint: fingerprint })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const encrypted_user_id = btoa(data.user_id);
                        localStorage.setItem("user_id", encrypted_user_id);
                        location.href = "../login/";
                    } else {
                        document.getElementById("message").innerText = data.message;
                    }
                });
        });
    </script>
</body>

</html>