<?php
namespace WOM;

class CryptoHelper {

    public static function LoadPublicKeyFromString($key) {
        $rsa = new \phpseclib\Crypt\RSA();
        if(!$rsa->loadKey($key)){
            \WOM\Logger::$Instance->error("Public key is invalid");
            throw new \InvalidArgumentException("Public key is invalid");
        }

        return $rsa;
    }

    public static function LoadPublicKeyFromPath($keyPath) {
        if(!file_exists($keyPath)){
            \WOM\Logger::$Instance->error("{$keyPath} public key file does not exist");
            throw new \InvalidArgumentException("{$keyPath} public key file does not exist");
        }

        $rsa = new \phpseclib\Crypt\RSA();
        if(!$rsa->loadKey(file_get_contents($keyPath))){
            \WOM\Logger::$Instance->error("{$keyPath} public key file is invalid");
            throw new \InvalidArgumentException("{$keyPath} public key file is invalid");
        }

        return $rsa;
    }

    public static function LoadPrivateKey($keyPath, $passphrase = null, $logger = null) {
        if(!file_exists($keyPath)){
            \WOM\Logger::$Instance->error("{$keyPath} private key file does not exist");
            throw new \InvalidArgumentException("{$keyPath} private key file does not exist");
        }

        // load private key
        $rsa = new \phpseclib\Crypt\RSA();
        if($passphrase != ''){
            $rsa->setPassword($passphrase);
        }
        if(!$rsa->loadKey(file_get_contents($keyPath))){
            \WOM\Logger::$Instance->error("{$keyPath} private key file is invalid");
            throw new \InvalidArgumentException("{$keyPath} private key file is invalid");
        }

        return $rsa;
    }

    public static function Encrypt($payload, $key){
        $key->setEncryptionMode(\phpseclib\Crypt\RSA::ENCRYPTION_PKCS1);

        return $key->encrypt($payload);
    }

    public static function Decrypt($payload, $key){
        $key->setEncryptionMode(\phpseclib\Crypt\RSA::ENCRYPTION_PKCS1);

        return $key->decrypt(base64_decode($payload));
    }

}
