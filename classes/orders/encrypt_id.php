<?php
function encrypt_id(int $id): string
{
    $key = hash("SHA3-512", getenv("MAGIC_CHAR"));
    $hashed_key = base64_encode(hash("sha256", $key));
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-256-cbc"));
    $encrypt_id = openssl_encrypt($id, "aes-256-cbc", $hashed_key, 0, $iv);
    return base64_encode($encrypt_id . "::" . $iv);
}