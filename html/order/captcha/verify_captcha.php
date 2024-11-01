<?php
session_start();

if (!isset($_SESSION["order"]["captcha"]["after"]["url"]) || $_SESSION["order"]["captcha"]["after"]["url"] === "") {
    $after_url = "/order/";
    $_SESSION["message_details"] = "本来表示するべきページへの宛先情報がなかったため、トップページに移動しました。";
} else {
    $after_url = $_SESSION["order"]["captcha"]["after"]["url"];
}

if (isset($_POST['captcha']) && $_POST['captcha'] == $_SESSION["order"]["captcha"]["code"]) {
    unset($_SESSION["order"]["captcha"]["code"]);
    $_SESSION["message"] = "認証に成功しました。";
    $_SESSION["message_type"] = "success";
    $_SESSION["order"]["captcha"]["success"] = true;
    $post_data = isset($_SESSION["order"]["captcha"]["post_data"]) ? $_SESSION["order"]["captcha"]["post_data"] : "";
    unset($_SESSION["order"]["captcha"]["after"]);
    unset($_SESSION["order"]["captcha"]["before"]);
    unset($_SESSION["order"]["captcha"]["post_data"]);
    session_write_close();
    if ($post_data) {
        ?>
        <form action="<?= htmlspecialchars($after_url) ?>" method="post" id="post_form">
            <?php foreach ($post_data as $key => $value): ?>
                <?php if (is_array($value)): ?>
                    <?php foreach ($value as $sub_key => $sub_value): ?>
                        <input type="hidden" name="<?= htmlspecialchars($key) ?>[<?= htmlspecialchars($sub_key) ?>]"
                            value="<?= htmlspecialchars($sub_value) ?>">
                    <?php endforeach ?>
                <?php else: ?>
                    <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                <?php endif ?>
            <?php endforeach ?>
        </form>
        <script>document.getElementById("post_form").submit();</script>
        <?php
    } else {
        header("Location: {$after_url}");
        exit();
    }
} else {
    $_SESSION["message"] = "認証に失敗しました。";
    $_SESSION["message_type"] = "danger";
    session_write_close();
    header("Location: ./");
    exit();
}
