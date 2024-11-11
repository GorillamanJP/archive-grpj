<?php
session_start();

$ok = true;
$_SESSION["message"] = "";

if (!isset($_POST["user_name"]) || $_POST["user_name"] === "") {
    $_SESSION["message"] .= "「ユーザー名」";
    $ok = false;
}
if (!isset($_POST["password"]) || $_POST["password"] === "") {
    $_SESSION["message"] .= "「パスワード」";
    $ok = false;
}

if ($ok) {
    $user_name = htmlspecialchars($_POST["user_name"]);
    $password = htmlspecialchars($_POST["password"]);
    try {
        require_once $_SERVER['DOCUMENT_ROOT']."/../classes/users/user.php";
        $user = new User();
        $user = $user->get_from_user_name($user_name);
        $user = $user->verify($password);
        session_regenerate_id(true);
        $_SESSION["login"]["user_id"] = $user->get_id();
        $_SESSION["login"]["last_activity"] = time();
        $_SESSION["login"]["token"] = hash("SHA3-512", $user->get_user_name() . $user->get_password_hash());
        $_SESSION["message"] = "ログインしました。";
        $_SESSION["message_type"] = "info";
        # ユーザーIDが1(初期ユーザーのadmin)の場合は警告メッセージを出す
        if ($user->get_id() == 1) {
            $_SESSION["message"] .= "このユーザーは初期ユーザーです。使用者のユーザーを新たに作成したうえで、このアカウントは早急に削除してください。";
            $_SESSION["message_type"] = "danger";
        }
        # ログイン後にリダイレクトがある場合
        if (isset($_SESSION["login"]["after"])) {
            $after = $_SESSION["login"]["after"];
            unset($_SESSION["login"]["after"]);
            # POSTデータを持っていた場合
            if (isset($after["post_data"])) {
                ?>
                <form action="<?= htmlspecialchars($after["url"]) ?>" method="post" id="post_form">
                    <?php foreach ($after["post_data"] as $key => $value): ?>
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
                session_write_close();
                header("Location: " . htmlspecialchars($after["url"]));
            }
        } else {
            session_write_close();
            header("Location: /regi/");
        }
        exit();
    } catch (\Throwable $e) {
        $_SESSION["message"] = "ユーザー名またはパスワードが違います。";
        $_SESSION["message_type"] = "danger";
        session_write_close();
        header("Location: ./");
    }
} else {
    $_SESSION["message"] .= "の項目が空になっています。";
    $_SESSION["message_type"] = "danger";
}
session_write_close();
header("Location: ./");
