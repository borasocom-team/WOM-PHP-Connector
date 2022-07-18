<?php
namespace WOM;

use WOM\Config\Domain;
use Ramsey\Uuid\Uuid;

class POS {
    private $log;
    private $id;
    private $registry;
    private $privKey;

    public function __construct($id, $privKeyPath, $privKeyPassword = null) {
        if(!$privKeyPassword) {
            $privKeyPassword = '';
        }

        \WOM\Logger::Initialize();

        $this->id = $id;
        $this->privKey = CryptoHelper::LoadPrivateKey($privKeyPath, $privKeyPassword);

        $this->registry = Registry::GetInstance(Domain::GetBaseUrl());
    }

    public function RequestPayment($amount, $pocketAckUrl, Filter $filter, $posAckUrl = null, $persistent = false, $nonce = null) {
        if(!is_integer($amount) || $amount < 1){
            \WOM\Logger::$Instance->error("Payment amount not valid (must be integer greater than 0)");
            throw new \InvalidArgumentException("Payment amount not valid (must be integer greater than 0)");
        }
        if($pocketAckUrl == null || !filter_var($pocketAckUrl, FILTER_VALIDATE_URL)) {
            \WOM\Logger::$Instance->error("Pocket confirmation URL must be a valid URL");
            throw new \InvalidArgumentException("Pocket confirmation URL must be a valid URL");
        }
        if($filter == null || !is_a($filter, '\WOM\Filter')){
            \WOM\Logger::$Instance->error("Filter must be set and valid");
            throw new \InvalidArgumentException("Filter must be set and valid");
        }
        if($posAckUrl != null && !filter_var($posAckUrl, FILTER_VALIDATE_URL)) {
            \WOM\Logger::$Instance->error("Ack confirmation URL must be a valid URL, if set");
            throw new \InvalidArgumentException("Ack confirmation URL must be a valid URL, if set");
        }

        \WOM\Logger::$Instance->debug("Performing payment generation request");

        $response_data = $this->PaymentRegister($amount, $pocketAckUrl, $filter, $posAckUrl, $persistent, $nonce);

        // call to voucher/verify API
        $this->PaymentVerify($response_data['otc']);

        \WOM\Logger::$Instance->info("Payment generated");

        $otc = $response_data['otc'];
        $password = $response_data['password'];
        return array($otc, $password);
    }

    private function PaymentRegister($amount, $pocketAckUrl, Filter $filter, $posAckUrl = null, $persistent = false, $nonce = null, $password = null) {
        if(!$nonce) {
            // Generate a unique nonce if there is none
            $nonce = Uuid::uuid4()->toString();
        }

        $payload = json_encode(array(
            'posId' => $this->id,
            'nonce' => $nonce,
            'password' => $password,
            'amount' => $amount,
            'simpleFilter' => $filter,
            'pocketAckUrl' => $pocketAckUrl,
            'posAckUrl' => $posAckUrl,
            'persistent' => $persistent
        ));

        $encryptedPayload = CryptoHelper::Encrypt($payload, $this->registry->publicKey);

        $jsonResponse = $this->registry->PaymentRegister($this->id, $nonce, base64_encode($encryptedPayload));

        $response = CryptoHelper::Decrypt($jsonResponse['payload'], $this->privKey);

        return json_decode($response, true);
    }

    private function PaymentVerify($otc) {
        $encryptedOtc = CryptoHelper::Encrypt(json_encode(array(
            'otc' =>$otc
        )), $this->registry->publicKey);

        $this->registry->PaymentVerify(base64_encode($encryptedOtc));
    }

    public function GetPaymentStatus($otc) {
        \WOM\Logger::$Instance->debug("Checking payment $otc status");

        $payload = json_encode(array(
            'posId' => $this->id,
            'otc' => $otc
        ));

        $encryptedPayload = CryptoHelper::Encrypt($payload, $this->registry->publicKey);

        $jsonResponse = $this->registry->GetPaymentStatus($this->id, base64_encode($encryptedPayload));

        $response = CryptoHelper::Decrypt($jsonResponse['payload'], $this->privKey);

        return json_decode($response, true);
    }

}
