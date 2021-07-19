<?php
namespace WOM\Config;

// WOM domain configuration, generates required API URLs.
class Domain {

    const PROD_DOMAIN = "wom.social";
    const DEV_DOMAIN = "dev.wom.social";

    private static $domain = self::PROD_DOMAIN;

    public static function GetDomain() {
        return self::$domain;
    }

    public static function SetDomain($dmn) {
        self::$domain = $dmn;
    }

    // Gets the base API URL.
    public static function GetBaseUrl() {
        return "http://" . self::$domain . "/api/v1/";
    }

    // Gets the full Registry public key URL.
    public static function GetRegistryPubKeyUrl() {
        return "http://" . self::$domain . "/api/v1/auth/key";
    }

}
