<?php

namespace AesHashing;

class AesHashing
{
    static function acrypt($password)
    {
        $rand = rand(10000,100000);
        for ($i = 0; $i <= $rand; $i++)
            $password = sha1($password);

        $cipher = "aes-256-gcm";

        $ivlen     = openssl_cipher_iv_length($cipher);
        $plaintext = openssl_random_pseudo_bytes($ivlen);
        $iv        = openssl_random_pseudo_bytes($ivlen);
        $hashed    = openssl_encrypt($plaintext, $cipher, $password, $options = 0, $iv, $tag);

        return base64_encode($plaintext) . "." . base64_encode($iv) . "." . base64_encode($tag) . "." . base64_encode($hashed);
    }

    static function checkAcrypt($password, $hashed)
    {
        $components = explode(".", $hashed);
        $cipher     = "aes-256-gcm";

        if (count($components) != 4) {
            return false;
        }

        for ($i = 0; $i <= 100000; $i++){
            $password = sha1($password);

            if($i>=10000){
                $result = openssl_decrypt(base64_decode(@$components[3]), $cipher, $password, $options = 0, base64_decode(@$components[1]), base64_decode(@$components[2]));

                if ($result === base64_decode($components[0])) {
                    return true;
                }
            }
        }

        return false;
    }
}
