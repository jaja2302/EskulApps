<?php

namespace App\Helpers;

class HashHelper
{
    private static string $key;

    public static function init()
    {
        self::$key = config('app.key');
    }

    public static function encrypt($value)
    {
        self::init();
        $encrypted = openssl_encrypt(
            (string)$value,
            'AES-256-ECB',
            self::$key,
            OPENSSL_RAW_DATA
        );
        
        // Menggunakan strtr untuk mengubah karakter yang tidak aman di URL
        return strtr(base64_encode($encrypted), [
            '+' => '-',
            '/' => '_',
            '=' => ''
        ]);
    }

    public static function decrypt($hash)
    {
        self::init();
        try {
            // Mengembalikan karakter base64 yang asli
            $base64 = strtr($hash, [
                '-' => '+',
                '_' => '/'
            ]);
            
            // Menambahkan padding '=' jika diperlukan
            $pad = strlen($base64) % 4;
            if ($pad) {
                $base64 .= str_repeat('=', 4 - $pad);
            }
            
            $decrypted = openssl_decrypt(
                base64_decode($base64),
                'AES-256-ECB',
                self::$key,
                OPENSSL_RAW_DATA
            );
            
            if ($decrypted === false) {
                throw new \Exception('Decryption failed');
            }
            
            return $decrypted;
        } catch (\Exception $e) {
            \Log::error('Decryption error: ' . $e->getMessage());
            return null;
        }
    }
} 