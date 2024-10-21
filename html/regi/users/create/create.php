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

    require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/user.php";
    try {
        $user = new User();
        $user = $user->create($user_name, $password);
        // 成功メッセージをセッションに保存
        $_SESSION['message'] = 'ユーザーが正常に登録されました。';
        $_SESSION['message_type'] = 'success';

        session_write_close();
        header('Location: /regi/users/login/');
        exit();
    } catch (\Throwable $e) {
        // エラーメッセージをセッションに保存
        $_SESSION['message'] = 'エラーが発生しました。おそらく、入力したユーザー名はすでに使用されています。';
        $_SESSION["message_details"] = $e->getMessage();
        $_SESSION['message_type'] = 'danger';
    }
} else {
    $_SESSION["message"] .= "の項目が空になっています。";
    $_SESSION["message_type"] = "danger";
}
session_write_close();
header("Location: ./");
exit();