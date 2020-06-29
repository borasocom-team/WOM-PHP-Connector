<?php


namespace WOM\config;

class Config
{
    // PROD
    const PROD_BASE_URL = "http://wom.social/api/v1/";
    const PROD_DEBUG = False;
    const PROD_WARNING = False;

    // DEV
    const DEV_BASE_URL = "http://dev.wom.social/api/v1/";
    const DEV_DEBUG = True;
    const DEV_WARNING = True;


    public static function GetBaseUrl(){
        return defined("DEV")? self::DEV_BASE_URL: self::PROD_BASE_URL;

    }

    public static function GetShowDebug(){
        return defined("DEV")? self::DEV_DEBUG: self::PROD_DEBUG;

    }

    public static function GetShowWarning(){
        return defined("DEV")? self::DEV_WARNING: self::PROD_WARNING;
    }

}
