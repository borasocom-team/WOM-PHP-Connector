<?php
namespace WOM;

use Monolog\Handler\StreamHandler;

class Logger {

    public static $Instance = null;

    // Initializes the WOM Connector logger with optional custom handlers.
    public static function Initialize($handlers = null) {
        if(Logger::$Instance != null) {
            return Logger::$Instance;
        }

        if($handlers == null) {
            // Setup default logging handlers
            $handlers = new StreamHandler('php://stderr', \Monolog\Logger::INFO);
        }

        Logger::$Instance = new \Monolog\Logger("WOM Connector");
        if(is_array($handlers)) {
            foreach($handlers as $handler) {
                Logger::$Instance->pushHandler($handler);
            }
        }
        else {
            Logger::$Instance->pushHandler($handlers);
        }

        return Logger::$Instance;
    }

}
