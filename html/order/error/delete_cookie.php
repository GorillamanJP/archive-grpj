<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/order/protects/protect.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/get_magic_char.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";

session_start();

if (!isset($_POST["pass_phrase"]) || $_POST["pass_phrase"] === "") {
    redirect_with_error("./", "パスワード未入力です。", "", "warning");
}

$domain_name = $_SERVER['SERVER_NAME'];
$magic_char = htmlspecialchars($_POST["pass_phrase"]);
if (get_magic_char() == hash("SHA3-256", $domain_name . $magic_char)) {
    setcookie("order", "", 0, "/");
    redirect_with_error("/order/", "クッキーを削除しました。", "", "info");
} else {
    redirect_with_error("./", "パスワードが違います。", "", "danger");
}