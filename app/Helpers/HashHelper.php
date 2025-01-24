<?php

namespace App\Helpers;

class HashHelper
{
    private static string $cipher = 'aes-256-cbc';
    private static string $key;

    public static function init()
    {
        self::$key = config('app.key');
    }

    public static function encrypt($value)
    {
        self::init();
        $ivLength = openssl_cipher_iv_length(self::$cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $encrypted = openssl_encrypt((string)$value, self::$cipher, self::$key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public static function decrypt($hash)
    {
        self::init();
        $decoded = base64_decode($hash);
        $ivLength = openssl_cipher_iv_length(self::$cipher);
        $iv = substr($decoded, 0, $ivLength);
        $encrypted = substr($decoded, $ivLength);
        return openssl_decrypt($encrypted, self::$cipher, self::$key, 0, $iv);
    }
} 