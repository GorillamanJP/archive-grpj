<?php
session_start();
if (!isset($_POST["id"]) || $_POST["id"] === "") {
    $_SESSION["message"] = "ユーザーのIDが指定されていません。";
    $_SESSION["message_details"] = "このメッセージが出る場合、内部のバグの可能性がありますので、「何を」「どのように」したらエラーが出たのかを開発者までお伝えください。\nご不便、ご迷惑をおかけして申し訳ありませんが、ご協力をお願いします。";
    $_SESSION["message_type"] = "danger";
    session_write_close();
    header("Location ../../");
    exit();
}
$id = htmlspecialchars($_POST["id"]);

$ok = true;
$_SESSION["message"] = "";

if(!isset($_POST["user_name"]) || $_POST["user_name"] === ""){
    $_SESSION["message"] .= "「ユーザー名」";
    $ok = false;
}
if(!isset($_POST["password"]) || $_POST["password"] === ""){
    $_SESSION["message"] .= "「パスワード」";
    $ok = false;
}

if ($ok) {
    $user_name = htmlspecialchars($_POST["user_name"]);
    $password = htmlspecialchars($_POST["password"]);

    try {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/user.php";
        $user = new User();
        $user = $user->get_from_id($id);
        $user = $user->update($user_name, $password);

        $_SESSION["message"] = "ユーザー情報を更新しました。";
        $_SESSION["message_type"] = "success";

        session_write_close();
        header("Location: ../list/");
        exit();
    } catch (\Throwable $e) {
        $_SESSION["message"] = "エラーが発生しました。";
        $_SESSION["message_details"] = $e->getMessage();
        $_SESSION["message_type"] = "danger";
    }
} else {
    $_SESSION["message"] .= "の項目が空になっています。";
    $_SESSION["message_type"] = "danger";
}
session_write_close();
?>
<form action="./" method="post" id="post_form">
    <input type="hidden" name="id" id="id" value="<?= $id ?>">
</form>
<script>document.getElementById("post_form").submit();</script>