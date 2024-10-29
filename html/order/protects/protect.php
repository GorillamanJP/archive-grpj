<?php
session_start();
function check_magic_char(string $magic_char)
{
    $server_magic_char = hash("sha256", getenv("PASS_PHRASE"));
    return $server_magic_char == $magic_char;
}

if (isset($_GET["magic_char"]) && $_GET["magic_char"] !== "") {
    if (check_magic_char($_GET["magic_char"])) {
        $_SESSION["order"]["magic_char"] = hash("sha256", getenv("PASS_PHRASE"));
    } else {
        http_response_code(403);
        session_write_close();
        exit(1);
    }
} else if (!isset($_SESSION["order"]["magic_char"]) || !check_magic_char($_SESSION["order"]["magic_char"])) {
    header("Location: /order/protects/");
    exit(1);
}

session_write_close();