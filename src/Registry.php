<?php
namespace WOM;

use Composer\Json\JsonValidationException;
use JsonSchema\Exception\JsonDecodingException;
use WOM\Config\Domain;

class Registry {
    private $client = null;
    public $publicKey;
    private $logger;

    private static $Instance = null;

    private function __construct($baseUrl) {
        $this->client = new RESTClient($baseUrl);
        $this->publicKey = $pubKey = CryptoHelper::LoadPublicKeyFromString($this->RefreshPublicKey());
    }

    public static function GetInstance($baseUrl){
        if(Registry::$Instance == null){
            Registry::$Instance = new Registry($baseUrl);
        }

        return Registry::$Instance;
    }

    public function VoucherCreate($source_id, $nonce, $payload) {
        $request_payload = json_encode(array(
            'sourceId' => $source_id,
            'nonce' => $nonce,
            'payload' => $payload
        ));
        if($request_payload == false){
            $this->LogJSONError();
        }
        return $this->client->VoucherCreate($request_payload);
    }

    public function VoucherVerify($payload) {
        $request_payload = json_encode(array('Payload' => $payload));

        if($request_payload == false){
            $this->LogJSONError();
        }

        $this->client->VoucherVerify($request_payload);
    }

    public function PaymentRegister($pos_id, $nonce, $payload) {
        $request_payload = json_encode(array(
            'posId' => $pos_id,
            'nonce' => $nonce,
            'payload' => $payload
        ));
        if($request_payload == false){
            $this->LogJSONError();
        }

        return $this->client->PaymentRegister($request_payload);
    }

    public function PaymentVerify($payload) {
        $request_payload = json_encode(array('Payload' => $payload));
        if($request_payload == false){
            $this->LogJSONError();
        }

        $this->client->PaymentVerify($request_payload);
    }

    public function GetPaymentStatus($pos_id, $payload) {
        $request_payload = json_encode(array(
            'posId' => $pos_id,
            'payload' => $payload
        ));
        if($request_payload == false){
            $this->LogJSONError();
        }

        $this->client->GetPaymentStatus($request_payload);
    }

    private function LogJSONError() {
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                \WOM\Logger::$Instance->debug('No errors');
                break;
            case JSON_ERROR_DEPTH:
                \WOM\Logger::$Instance->error('Maximum stack depth exceeded');
                throw new JsonValidationException('Maximum stack depth exceeded');
                break;
            case JSON_ERROR_STATE_MISMATCH:
                \WOM\Logger::$Instance->error('Underflow or the modes mismatch');
                throw new JsonValidationException('Underflow or the modes mismatch');
                break;
            case JSON_ERROR_CTRL_CHAR:
                \WOM\Logger::$Instance->error('Unexpected control character found');
                throw new JsonValidationException('Unexpected control character found');
                break;
            case JSON_ERROR_SYNTAX:
                \WOM\Logger::$Instance->error('Syntax error, malformed JSON');
                throw new JsonValidationException('Syntax error, malformed JSON');
                break;
            case JSON_ERROR_UTF8:
                \WOM\Logger::$Instance->error('Malformed UTF-8 characters, possibly incorrectly encoded');
                throw new JsonValidationException('Malformed UTF-8 characters, possibly incorrectly encoded');
                break;
            default:
                \WOM\Logger::$Instance->error('Unknown error');
                throw new JsonValidationException('Unknown error');
                break;
        }
    }

    private function RefreshPublicKey() {
        $pubKey = file_get_contents(Domain::GetRegistryPubKeyUrl());
        if(!$pubKey) {
            throw new \Exception("Can't refresh Registry public key");
        }

        return $pubKey;
    }

}
