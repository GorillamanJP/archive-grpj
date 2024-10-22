<?php
session_start();

// ロックファイルのパス
$lockFilePath = "/tmp/sales_create.lock";

// ロックファイルが存在するかチェック
if (file_exists($lockFilePath)) {
    // ロックファイルのタイムスタンプを更新
    $lockFile = fopen($lockFilePath, "w");
    fwrite($lockFile, time());
    fclose($lockFile);
    echo "keepalive success";
} else {
    echo "lock file not found";
}
