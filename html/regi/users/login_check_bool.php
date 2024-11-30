<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/users/user.php";
function login_check(): bool
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION["login"]["user_id"]) || $_SESSION["login"]["user_id"] === "") {
        session_write_close();
        return false;
    }
    try {
        $id = $_SESSION["login"]["user_id"];
        $user = new User();
        $user->get_from_id($id);
        if (!isset($_SESSION["login"]["token"]) || $_SESSION["login"]["token"] !== hash("SHA3-512", $user->get_user_name() . $user->get_password_hash())) {
            session_write_close();
            return false;
        }
        $_SESSION["login"]["last_activity"] = time();
        session_write_close();
        return true;
    } catch (\Throwable $th) {
        session_write_close();
        return false;
    }
}