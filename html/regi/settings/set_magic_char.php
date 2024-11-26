<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check.php";
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/redirect_with_error.php";
session_start();

if (!isset($_POST["magic_char"]) || $_POST["magic_char"] === "") {
    redirect_with_error("./", "合言葉の項目が空になっています。", "", "warning");
}

$new_magic_char = htmlspecialchars($_POST["magic_char"]);

$env_file = $_SERVER['DOCUMENT_ROOT'] . "/../.env";
$file_lines = file($env_file);
foreach ($file_lines as &$line) {
    if (strpos($line, "PASS_PHRASE=") === 0) {
        $line = "PASS_PHRASE={$new_magic_char}";
        putenv("PASS_PHRASE={$new_magic_char}");
        break;
    }
}
// file_put_contents($env_file, implode("\n", $file_lines));
redirect_with_error("./", "設定を反映しました。", "", "success");
