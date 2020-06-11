<?php



namespace WOM;

require __DIR__ . '\..\vendor\autoload.php';

use WOM\config\Config;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;



class Instrument
{
    private $log;

    function __construct()
    {
        $this->log = new Logger('Instrument');
        $this->log->pushHandler(new StreamHandler('warning.log', Logger::WARNING));
        $this->log->pushHandler(new StreamHandler('error.log', Logger::ERROR));
        if(Config::DEBUG){
            $this->log->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
        }

    }

    public function Generate(){
        $this->log->debug("Generated!");
        $this->log->debug("Generated!");
    }

}