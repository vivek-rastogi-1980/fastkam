<?php

namespace App\Services;

use Exception;

/**
 * RechPayChecksum
 * 
 * RechPay uses checksum signatures to ensure that API requests and responses shared
 * between your application and RechPay / ApnaCSC over the network have not been tampered with.
 * SHA256 hashing and AES-128-CBC encryption algorithm are utilized to ensure transaction data integrity.
 * 
 * Source: C:\Users\Lenovo\Downloads\Config_RechPay.php\RechPayChecksum.php
 * @version 1.0
 * @link https://rechpay.in/
 */
class RechPayChecksum
{
    private static string $iv = "@@@@&&&&####$$$$";

    /**
     * Encrypt an input string with AES-128-CBC using the provided merchant key.
     */
    public static function encrypt(string $input, string $key): string
    {
        $key = html_entity_decode($key);

        if (function_exists('openssl_encrypt')) {
            $data = openssl_encrypt($input, "AES-128-CBC", $key, 0, self::$iv);
            if ($data === false) {
                throw new Exception('OpenSSL failed to encrypt data with AES-128-CBC.');
            }
            return $data;
        }

        throw new Exception('OpenSSL extension is required for RechPay encryption.');
    }

    /**
     * Decrypt an encrypted string with AES-128-CBC using the provided merchant key.
     */
    public static function decrypt(string $encrypted, string $key): string
    {
        $key = html_entity_decode($key);

        if (function_exists('openssl_decrypt')) {
            $data = openssl_decrypt($encrypted, "AES-128-CBC", $key, 0, self::$iv);
            if ($data === false) {
                return '';
            }
            return $data;
        }

        throw new Exception('OpenSSL extension is required for RechPay decryption.');
    }

    /**
     * Generate a cryptographic checksum signature for request parameters.
     * 
     * @param array<string, mixed>|string $params
     * @param string $key Merchant Secret Key
     * @return string Base64 encoded encrypted signature
     */
    public static function generateSignature(array|string $params, string $key): string
    {
        if (!is_array($params) && !is_string($params)) {
            throw new Exception("String or array expected, " . gettype($params) . " given.");
        }

        if (is_array($params)) {
            // Exclude signature / checksum fields from the signature string calculation
            if (isset($params['CHECKSUMHASH'])) {
                unset($params['CHECKSUMHASH']);
            }
            if (isset($params['checksum'])) {
                unset($params['checksum']);
            }
            $params = self::getStringByParams($params);
        }

        return self::generateSignatureByString($params, $key);
    }

    /**
     * Verify whether a given checksum signature is authentic for the parameters.
     * 
     * @param array<string, mixed>|string $params
     * @param string $key Merchant Secret Key
     * @param string $checksum Received checksum to verify
     * @return bool
     */
    public static function verifySignature(array|string $params, string $key, string $checksum): bool
    {
        if (empty($checksum) || empty($key)) {
            return false;
        }

        if (!is_array($params) && !is_string($params)) {
            throw new Exception("String or array expected, " . gettype($params) . " given.");
        }

        if (is_array($params)) {
            if (isset($params['CHECKSUMHASH'])) {
                unset($params['CHECKSUMHASH']);
            }
            if (isset($params['checksum'])) {
                unset($params['checksum']);
            }
            if (isset($params['hash'])) {
                unset($params['hash']);
            }
            $params = self::getStringByParams($params);
        }

        return self::verifySignatureByString($params, $key, $checksum);
    }

    /**
     * Decrypt encrypted payload bundle delivered in format iv:salt_component:ciphertext.
     */
    public static function hashDecrypt(string $msgEncryptedBundle, string $password): string|false
    {
        $password = sha1($password);
        $components = explode(':', $msgEncryptedBundle);

        if (count($components) < 3) {
            return false;
        }

        $iv = $components[0];
        $salt = hash('sha256', $password . $components[1]);
        $encryptedMsg = $components[2];

        $decryptedMsg = openssl_decrypt($encryptedMsg, 'aes-256-cbc', $salt, 0, $iv);
        if ($decryptedMsg === false) {
            return false;
        }

        return $decryptedMsg;
    }

    private static function generateSignatureByString(string $params, string $key): string
    {
        $salt = self::generateRandomString(4);
        return self::calculateChecksum($params, $key, $salt);
    }

    private static function verifySignatureByString(string $params, string $key, string $checksum): bool
    {
        $rechPayHash = self::decrypt($checksum, $key);
        if (strlen($rechPayHash) < 5) {
            return false;
        }
        $salt = substr($rechPayHash, -4);
        return hash_equals($rechPayHash, self::calculateHash($params, $salt));
    }

    private static function generateRandomString(int $length): string
    {
        $random = "";
        $data = "9876543210ZYXWVUTSRQPONMLKJIHGFEDCBAabcdefghijklmnopqrstuvwxyz!@#$&_";
        $dataLen = strlen($data);

        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, random_int(0, $dataLen - 1), 1);
        }

        return $random;
    }

    private static function getStringByParams(array $params): string
    {
        ksort($params);
        $params = array_map(function ($value) {
            return ($value !== null && strtolower((string) $value) !== "null") ? (string) $value : "";
        }, $params);

        return implode("|", $params);
    }

    private static function calculateHash(string $params, string $salt): string
    {
        $finalString = $params . "|" . $salt;
        $hash = hash("sha256", $finalString);
        return $hash . $salt;
    }

    private static function calculateChecksum(string $params, string $key, string $salt): string
    {
        $hashString = self::calculateHash($params, $salt);
        return self::encrypt($hashString, $key);
    }
}

if (!function_exists('hash_decrypt')) {
    /**
     * Standalone compatibility function matching RechPayChecksum.php.
     */
    function hash_decrypt(string $msg_encrypted_bundle, string $password): string|false
    {
        return RechPayChecksum::hashDecrypt($msg_encrypted_bundle, $password);
    }
}
