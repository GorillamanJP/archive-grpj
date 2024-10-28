<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
</head>

<body>
    <h1>ログイン中です。しばらくお待ちください…</h1>
    <p id="message"></p>
    <script src="/common/get_fingerprint.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const encrypted_user_id = localStorage.getItem("user_id");
            if (!encrypted_user_id) {
                location.href = "../create/";
            }
            const user_id = atob(encrypted_user_id);
            const fingerprint = get_fingerprint();
            fetch("./login.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ user_id: user_id, fingerprint: fingerprint })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById("message").innerText = "ログイン成功";
                    } else {
                        document.getElementById("message").innerText = "ログイン失敗";
                    }
                });
        });
    </script>
</body>

</html>