<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check_bool.php";
if(!login_check()){
    http_response_code(403);
    exit();
}
?>
<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

// ロック解除
$lockfile_path = "/tmp/sales_create.lock";

if (file_exists($lockfile_path)) {
    $lockFile = fopen($lockfile_path, "r+");
    $lock_data = json_decode(fread($lockFile, filesize($lockfile_path)), true);
    fclose($lockFile);

    // ロックをかけたユーザーが現在のユーザーと一致するか確認
    if ($lock_data['user_id'] == $_SESSION["login"]['user_id']) {
        unlink($lockfile_path);
    }
}