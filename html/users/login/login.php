<?php
session_start();

if (!isset($_POST["user_name"], $_POST["password"])) {
    $_SESSION["error"]["message"] = "ユーザー名またはパスワードを入力してください。";
    header("Location: ./");
    exit();
}

$user_name = htmlspecialchars($_POST["user_name"], encoding: "UTF-8");
$password = htmlspecialchars($_POST["password"], encoding: "UTF-8");


try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/user.php";
    $user = new User();
    $user = $user->get_from_user_name($user_name);
    $user = $user->verify($password);
    $_SESSION["login"]["user_id"] = $user->get_id();
    # ログイン後にリダイレクトがある場合
    if (isset($_SESSION["login"]["after"])) {
        # POSTデータを持っていた場合
        if (isset($_SESSION["login"]["after"]["post_data"])) {
            ?>
            <form action="<?= $_SESSION["login"]["after"]["url"] ?>" method="post" id="post_form">
                <?php foreach ($_SESSION["login"]["after"]["post_data"] as $key => $value): ?>
                    <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                <?php endforeach ?>
            </form>
            <script>document.getElementById("post_form").submit();</script>
            <?php
        } else {
            header("Location: " . $_SESSION["login"]["after"]["url"]);
        }
    } else {
        header("Location: /regi/");
    }
    exit();
} catch (Exception $e) {
    $_SESSION["error"]["message"] = "ユーザー名またはパスワードが違います。";
    header("Location: ./");
}