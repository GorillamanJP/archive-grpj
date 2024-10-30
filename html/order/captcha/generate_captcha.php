<?php
session_start();
// 文字列を定義
$characters = '0123456789'; // 半角数字
$characters .= "あいうえおかきくけこさしすせそたちつてとなにぬねのはひふへほまみむめもやゆよらりるれろわをん"; // ひらがな
// $characters .= "アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン"; // カタカナ　フォントの都合で除外
// $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"; // アルファベット　フォントの都合で除外
// 文字列を配列に変換
$characters_array = mb_str_split($characters);
// 配列をシャッフル
shuffle($characters_array);
// シャッフルされた文字列を結合
$captcha_code = implode('', array_slice($characters_array, 0, 8));
$_SESSION["order"]["captcha"]["code"] = $captcha_code;

header('Content-Type: image/png');
$image = imagecreatetruecolor(400, 120); // 画像サイズを2倍に
$bg_color = imagecolorallocate($image, 255, 255, 255);
$line_color = imagecolorallocate($image, 64, 64, 64); // ラインの色
imagefilledrectangle($image, 0, 0, 400, 120, $bg_color);

// ランダムな線を引く
for ($i = 0; $i < 10; $i++) { // ラインの数も増やします
    imageline($image, mt_rand(0, 400), mt_rand(0, 120), mt_rand(0, 400), mt_rand(0, 120), $line_color);
}

$font = '/var/www/html/order/captcha/yojo.ttf'; // 指定のフォントパス
for ($i = 0; $i < mb_strlen($captcha_code); $i++) { // mb_strlen関数でマルチバイト文字を考慮
    $angle = mt_rand(-30, 30); // 文字の角度をランダムに設定
    $x = 40 + ($i * 40); // 文字の位置を設定
    $y = mt_rand(60, 100); // 文字の高さをランダムに設定
    $txt_color = imagecolorallocate($image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)); // 文字の色をランダムに設定
    imagettftext($image, 40, $angle, $x, $y, $txt_color, $font, mb_substr($captcha_code, $i, 1)); // フォントサイズも2倍に
}

imagepng($image);
imagedestroy($image);