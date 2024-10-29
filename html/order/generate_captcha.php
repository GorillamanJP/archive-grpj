<?php
session_start();
$captcha_code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 6);
$_SESSION['captcha_code'] = $captcha_code;

header('Content-Type: image/png');
$image = imagecreatetruecolor(100, 30);
$bg_color = imagecolorallocate($image, 255, 255, 255);
$txt_color = imagecolorallocate($image, 0, 0, 0);
imagefilledrectangle($image, 0, 0, 100, 30, $bg_color);
imagestring($image, 5, 10, 5, $captcha_code, $txt_color);
imagepng($image);
imagedestroy($image);
