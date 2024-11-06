<?php
function decrypt_id(string $encrypt_id){
    $key = hash("SHA3-512", getenv("MAGIC_CHAR"));
    $hashed_key = base64_encode(hash("sha256", $key));
    list($encrypt_data, $iv) = explode("::", base64_decode($encrypt_id), 2);
    return openssl_decrypt($encrypt_data, "aes-256-cbc", $hashed_key, 0, $iv);
}