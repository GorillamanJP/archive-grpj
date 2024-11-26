<?php
function redirect_with_error(string $redirect_url, string $message, string $message_details, string $message_type): never
{
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    $_SESSION["message"] = $message;
    $_SESSION["message_details"] = $message_details;
    $_SESSION["message_type"] = $message_type;
    session_write_close();
    header("Location: {$redirect_url}");
    exit();
}