<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/users/user.php";
function login_check():bool{
    session_start();
    if(!isset($_SESSION["login"]["user_id"]) || $_SESSION["login"]["user_id"] === ""){
        return false;
    }
    try {
        $id = $_SESSION["login"]["user_id"];
        $user = new User();
        $user->get_from_id($id);
        return true;
    }catch(\Throwable $th){
        return false;
    }
}