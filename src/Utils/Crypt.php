<?php

namespace App\Utils;

class Crypt
{

    private $encrypt_method = 'AES-256-CBC';
    private $secret_key = 'ZdEywgzaPzpFtzsZKz0h';
    private $secret_iv = 'uRPdOfIzWCxrKpksQRPr';

    public function encrypt($string)
    {
        $key = hash('sha256', $this->secret_key);
        $iv = substr(hash('sha256', $this->secret_iv), 0, 16); // sha256 is hash_hmac_algo
        $output = openssl_encrypt($string, $this->encrypt_method, $key, 0, $iv);
        return base64_encode($output);
    }

    public function decrypt($string)
    {
        $key = hash('sha256', $this->secret_key);
        $iv = substr(hash('sha256', $this->secret_iv), 0, 16);
        return openssl_decrypt(base64_decode($string), $this->encrypt_method, $key, 0, $iv);
    }
}
