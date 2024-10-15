<?php
session_start();
if (isset($_POST["user_name"], $_POST["password"])) {
    $user_name = htmlspecialchars($_POST["user_name"]);
    $password = htmlspecialchars($_POST["password"]);

    require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/user.php";
    try {
        $user = new User();
        $user = $user->create($user_name, $password);
        // 成功メッセージをセッションに保存
        $_SESSION['message'] = 'ユーザーが正常に登録されました。';
        $_SESSION['message_type'] = 'success';
    } catch (\Throwable $e) {
        // エラーメッセージをセッションに保存
        $_SESSION['message'] = 'エラーが発生しました: ' . $e->getMessage();
        $_SESSION['message_type'] = 'danger';
    }
    // 商品一覧ページへリダイレクト
    header('Location: /users/login/index.php');
    exit();
}