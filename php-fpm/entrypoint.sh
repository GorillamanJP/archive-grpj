#!/bin/bash

# マウントされたディレクトリの所有者を変更
chown -R www-data:www-data /var/www

# 指定したコマンドを実行
exec "$@"
