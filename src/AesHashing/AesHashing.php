<?php

namespace AesHashing;

class AesHashing
{
    static function acrypt($password)
    {
        $plaintext = str_random(8);
        $cipher = "aes-256-gcm";

        $ivlen  = openssl_cipher_iv_length($cipher);
        $iv     = openssl_random_pseudo_bytes($ivlen);
        $hashed = openssl_encrypt($plaintext, $cipher, $password, $options = 0, $iv, $tag);

        return $plaintext . "." . base64_encode($iv)."." . base64_encode($tag). "." . base64_encode($hashed);
    }

    static function checkAcrypt($password, $hashed)
    {
        $components = explode(".", $hashed);
        $cipher = "aes-256-gcm";

        if(count($components)!=4){
            return false;
        }

        $result = openssl_decrypt(base64_decode(@$components[3]), $cipher, $password, $options = 0, base64_decode(@$components[1]), base64_decode(@$components[2]));

        if($result===$components[0]){
            return true;
        }

        return false;
    }
}
