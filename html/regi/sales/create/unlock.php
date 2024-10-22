<?php
// ロック解除
$lockfile_path = "/tmp/sales_create.lock";
$lockfile = fopen($lockfile_path,"c+");

flock($lockfile, LOCK_EX);
ftruncate($lockfile,0);
fwrite($lockfile,"0");
fflush($lockfile);
flock($lockfile, LOCK_UN);
fclose($lockfile);