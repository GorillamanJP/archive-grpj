<style>
    .custom-alert {
        position: fixed;
        top: 100px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1050;
        display: none;
        padding: 10px 40px 10px 10px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .custom-alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .custom-alert-warning {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }

    .custom-alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .custom-alert-info {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .btn-close-alert {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        /* ボタンを垂直中央に配置 */
        background: none;
        border: none;
        font-size: 1.2em;
        cursor: pointer;
        color: inherit;
        /* 親要素の色を継承 */
        line-height: 1;
        /* ボタン内の中央に「×」を配置 */
        align-items: center;
        justify-content: center;
    }
</style>
<?php
// メッセージとメッセージタイプがある場合に取得
$message = htmlspecialchars(isset($_SESSION['message']) ? $_SESSION['message'] : '');
$message_details = htmlspecialchars(isset($_SESSION['message_details']) ? $_SESSION['message_details'] : '');
$message_type = htmlspecialchars(isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '');

// メッセージ表示後、セッションから削除
unset($_SESSION['message']);
unset($_SESSION['message_details']);
unset($_SESSION['message_type']);
?>
<?php if ($message): ?>
    <div id="customAlert" class="custom-alert custom-alert-<?= $message_type ?>">
        <div class="message-content">
            <?= $message ?>
            <?php if ($message_details !== ''): ?>
                <u data-bs-toggle="collapse" data-bs-target="#details" aria-expanded="false"
                    aria-controls="details"><b>詳細</b></u>
                <div class="collapse" id="details">
                    <?= $message_details ?>
                </div>
            <?php endif ?>
        </div>
        <button type="button" class="btn-close-alert" aria-label="Close" onclick="hideCustomAlert()"><i class="bi bi-x-circle"></i></button>
    </div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const customAlert = document.getElementById('customAlert');
        if (customAlert) {
            setTimeout(() => {
                customAlert.style.display = 'block';
            }, 100); // 少し遅延をつけて表示する（必要に応じて調整）
            setTimeout(() => {
                customAlert.style.display = 'none';
            }, 20000); // 20秒後に自動的に消える
        }
    });

    function showCustomAlert(message, type) {
        const alertBox = document.getElementById('customAlert');
        alertBox.className = `custom-alert custom-alert-${type}`; // クラスを設定
        alertBox.innerText = message;
        alertBox.style.display = 'block';
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 5000); // 5秒後に自動的に消える
    }

    function hideCustomAlert() {
        const customAlert = document.getElementById('customAlert');
        if (customAlert) {
            customAlert.style.display = 'none';
        }
    }
</script>