<?php
session_start();

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
