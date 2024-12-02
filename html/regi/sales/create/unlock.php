<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check_bool.php";
if (!login_check()) {
    http_response_code(403);
    exit();
}
?>
<?php
session_start();


// // ロック解除
// $lockfile_path = "/tmp/sales_create.lock";

// if (file_exists($lockfile_path)) {
//     $lockFile = fopen($lockfile_path, "r+");
//     $lock_data = json_decode(fread($lockFile, filesize($lockfile_path)), true);
//     fclose($lockFile);

//     // ロックをかけたユーザーが現在のユーザーと一致するか確認
//     if ($lock_data['user_id'] == $_SESSION["login"]['user_id']) {
//         unlink($lockfile_path);
//     }
// }

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/purchases/purchase.php";
    $temp_purchase_id = isset($_SESSION["temp_purchase"]["id"]) ? $_SESSION["temp_purchase"]["id"] : null;
    $purchase = new Purchases();
    if (isset($temp_purchase_id)) {
        $purchase = $purchase->get_from_temp_purchases_id($temp_purchase_id);
        $purchase->delete();
        unset($_SESSION["temp_purchase"]);
        session_write_close();
    }
} catch (Throwable $th) {
    http_response_code(500);
}