<?php
$user_name = htmlspecialchars($_POST["user_name"], encoding: "UTF-8");
$password = htmlspecialchars($_POST["password"], encoding: "UTF-8");
if(login($user_name, $password)){
    header("Location: ./login_success.html");
} else{
    session_start();
    $_SESSION["error"]["message"] = "ユーザー名またはパスワードが違います。";
    header("Location: ./");
};

function login($user_name, $password):bool
{
    require_once $_SERVER['DOCUMENT_ROOT'] . "/users/user.php";
    $user = new User();
    $user = $user->get_from_user_name($user_name);
    if (!is_null($user)) {
        $user = $user->verify($password);
        if (!is_null($user->verify($password))) {
            return true;
        }
    }
    return false;
}