<?php
// ロック解除
$lockfile_path = "/tmp/sales_create.lock";

if(file_exists($lockfile_path)){
    unlink($lockfile_path);
}