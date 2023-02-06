<?php
namespace WOM;

class CryptoHelper {

    public static function LoadPublicKeyFromString($key) {
        $key = \phpseclib3\Crypt\PublicKeyLoader::load($key);
        if(!$key){
            \WOM\Logger::$Instance->error("Public key is invalid");
            throw new \InvalidArgumentException("Public key is invalid");
        }
        if(!$key instanceof \phpseclib3\Crypt\Common\PublicKey) {
            \WOM\Logger::$Instance->error("Loaded key if not public");
            throw new \InvalidArgumentException("Key is not public");
        }

        \WOM\Logger::$Instance->debug("Public key loaded successfully from string");

        return $key;
    }

    public static function LoadPublicKeyFromPath($keyPath) {
        if(!file_exists($keyPath)){
            \WOM\Logger::$Instance->error("{$keyPath} public key file does not exist");
            throw new \InvalidArgumentException("{$keyPath} public key file does not exist");
        }

        $key = \phpseclib3\Crypt\PublicKeyLoader::load(file_get_contents($keyPath));
        if(!$key){
            \WOM\Logger::$Instance->error("{$keyPath} public key file is invalid");
            throw new \InvalidArgumentException("{$keyPath} public key file is invalid");
        }
        if(!$key instanceof \phpseclib3\Crypt\Common\PublicKey) {
            \WOM\Logger::$Instance->error("Loaded key if not public");
            throw new \InvalidArgumentException("Key is not public");
        }

        \WOM\Logger::$Instance->debug("Public key loaded successfully from path {$keyPath}");

        return $key;
    }

    public static function LoadPrivateKey($keyPath, $passphrase = null, $logger = null) {
        if(!file_exists($keyPath)){
            \WOM\Logger::$Instance->error("{$keyPath} private key file does not exist");
            throw new \InvalidArgumentException("{$keyPath} private key file does not exist");
        }

        $key = \phpseclib3\Crypt\PublicKeyLoader::load(file_get_contents($keyPath), $password = $passphrase);
        if(!$key){
            \WOM\Logger::$Instance->error("{$keyPath} private key file is invalid");
            throw new \InvalidArgumentException("{$keyPath} private key file is invalid");
        }
        if(!$key instanceof \phpseclib3\Crypt\Common\PrivateKey) {
            \WOM\Logger::$Instance->error("Loaded key if not private");
            throw new \InvalidArgumentException("Key is not private");
        }

        \WOM\Logger::$Instance->debug("Private key loaded successfully from path {$keyPath}");

        return $key;
    }

    public static function Encrypt($payload, $key){
        $key = $key->withPadding(\phpseclib3\Crypt\RSA::ENCRYPTION_PKCS1);

        \WOM\Logger::$Instance->debug("Encrypting payload of " . mb_strlen($payload) . " characters");
        return $key->encrypt($payload);
    }

    public static function Decrypt($payload, $key){
        $key = $key->withPadding(\phpseclib3\Crypt\RSA::ENCRYPTION_PKCS1);

        \WOM\Logger::$Instance->debug("Decrypting payload of " . mb_strlen($payload) . " characters");
        return $key->decrypt(base64_decode($payload));
    }

}
