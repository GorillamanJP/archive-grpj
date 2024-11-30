<?php
function verify_int_value(...$values): bool
{
    foreach ($values as $value) {
        # 入力内容がそもそも数値でないことが疑われる場合
        if (is_numeric($value) && floor($value) != $value) {
            return false;
        }
        # 入力内容が32bit符号付整数の幅を超える場合
        # データベースは64bit符号付整数まで受け入れるが、それだと計算結果が64bitの幅をはみ出すと問題になるので未然に防ぐためにこのような実装にした
        if ($value > 2147483647 || $value < -2147483648) {
            return false;
        }
    }
    return true;
}