#!/bin/sh

# ランダムな128文字を生成して環境変数に設定
cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 128 | head -n 1 > /var/www/.magic_char

# ファイル末尾の改行を削除
truncate -s -1 /var/www/.magic_char

# PHP-FPMを起動
php-fpm
