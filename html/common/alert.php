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
    <div class="alert alert-<?= $message_type ?> alert-dismissible fade show my-2" role="alert">
        <p class="m-0">
            <?= $message ?>
            <?php if ($message_details !== ''): ?>
                <u data-bs-toggle="collapse" data-bs-target="#details" aria-expanded="false"
                    aria-controls="details"><b>詳細</b></u>
            <?php endif ?>
        </p>
        <div class="collapse" id="details">
            <?= $message_details ?>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>