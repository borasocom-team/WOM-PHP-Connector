<?php


namespace WOM;


class Registry
{
    private $client = null;
    public $publicKey;
    private $logger;

    private static $Instance = null;

    private function __construct(string $baseUrl, $publicKey)
    {
        $this->client = new RESTClient($baseUrl);
        $this->publicKey = $publicKey;
    }

    public static function GetInstance(string $baseUrl, $publicKey){
        if(Registry::$Instance == null){
            Registry::$Instance = new Registry($baseUrl, $publicKey);
        }

        return Registry::$Instance;
    }

    public function VoucherCreate(string $source_id, string $nonce, string $payload)
    {
        $request_payload = json_encode(array('SourceId' => $source_id, 'Nonce' => $nonce, 'Payload' => $payload));
        if($request_payload == false){
            $this->LogJSONError();
        }
        return $this->client->VoucherCreate($request_payload);
    }

    public function VoucherVerify(string $payload)
    {
        $request_payload = json_encode(array('Payload' => $payload));

        if($request_payload == false){
            $this->LogJSONError();
        }

        $this->client->VoucherVerify($request_payload);
    }

    public function PaymentRegister(string $pos_id, string $nonce, string $payload)
    {
        $request_payload = json_encode(array('PosId' => $pos_id, 'Nonce' => $nonce, 'Payload' => $payload));
        if($request_payload == false){
            $this->LogJSONError();
        }

        return $this->client->PaymentRegister($request_payload);
    }

    public function PaymentVerify(string $payload)
    {
        $request_payload = json_encode(array('Payload' => $payload));
        if($request_payload == false){
            $this->LogJSONError();
        }

        $this->client->PaymentVerify($request_payload);
    }


    private function LogJSONError(){
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                \WOM\Logger::$Instance->debug('No errors');
                break;
            case JSON_ERROR_DEPTH:
                \WOM\Logger::$Instance->debug('Maximum stack depth exceeded');
                break;
            case JSON_ERROR_STATE_MISMATCH:
                \WOM\Logger::$Instance->debug('Underflow or the modes mismatch');
                break;
            case JSON_ERROR_CTRL_CHAR:
                \WOM\Logger::$Instance->debug('Unexpected control character found');
                break;
            case JSON_ERROR_SYNTAX:
                \WOM\Logger::$Instance->debug('Syntax error, malformed JSON');
                break;
            case JSON_ERROR_UTF8:
                \WOM\Logger::$Instance->debug('Malformed UTF-8 characters, possibly incorrectly encoded');
                break;
            default:
                \WOM\Logger::$Instance->debug('Unknown error');
                break;
        }
        exit(1);
    }
}