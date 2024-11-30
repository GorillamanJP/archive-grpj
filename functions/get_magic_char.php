<?php
function get_magic_char(): string
{
    $domain_name = $_SERVER['SERVER_NAME'];
    $magic_char_env = getenv("PASS_PHRASE");
    return hash("SHA3-256", $domain_name . $magic_char_env);
}