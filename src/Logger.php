<?php
namespace WOM;

use Monolog\Handler\StreamHandler;

class Logger {
    public static $Instance = null;

    public static function Initialize() {
        if(Logger::$Instance == null){
            Logger::$Instance = new \Monolog\Logger("WOM Connector");
            Logger::$Instance->pushHandler(new StreamHandler('php://stderr', \Monolog\Logger::DEBUG));
        }
    }

}
