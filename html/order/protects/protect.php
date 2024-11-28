<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../functions/get_magic_char.php";
session_start();
function check_magic_char(string $magic_char)
{
    $server_magic_char = get_magic_char();
    return $server_magic_char == $magic_char;
}

if (isset($_GET["magic_char"]) && $_GET["magic_char"] !== "") {
    if (check_magic_char($_GET["magic_char"])) {
        $_SESSION["order"]["magic_char"] = get_magic_char();
    } else {
        session_write_close();
        header("Location: /order/protects/");
        exit();
    }
} else if (!isset($_SESSION["order"]["magic_char"]) || !check_magic_char($_SESSION["order"]["magic_char"])) {
    session_write_close();
    header("Location: /order/protects/");
    exit();
}

session_write_close();