<?php
session_start();

// ロックファイルのパス
$lockFilePath = "/tmp/sales_create.lock";

// ロックファイルが存在するかチェック
if (file_exists($lockFilePath)) {
    // ロックファイルのタイムスタンプを更新
    $lockFile = fopen($lockFilePath, "r+");
    $lock_data = json_decode(fread($lockFile, filesize($lockFilePath)), true);
    fclose($lockFile);

    // ロックをかけたユーザーが現在のユーザーと一致するか確認
    if ($lock_data['user_id'] == $_SESSION["login"]['user_id']) {
        $lockFile = fopen($lockFilePath, "w");
        fwrite($lockFile, json_encode(['time' => time(), 'user_id' => $_SESSION["login"]['user_id']]));
        fclose($lockFile);
        echo "keepalive success";
    }
}