<?php
namespace WOM;

use WOM\Config\Domain;
use Ramsey\Uuid\Uuid;

class Instrument {
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

    public function RequestVouchers($vouchers, $nonce = null, $password = null) {
        if(!is_array($vouchers) or count($vouchers) == 0 or !is_a($vouchers[0], '\WOM\Voucher')){
            throw new \InvalidArgumentException("Voucher list not valid or empty");
        }

        \WOM\Logger::$Instance->debug("Performing voucher generation request");

        $response_data = $this->VoucherCreate($vouchers, $nonce, $password);

        $this->VoucherVerify($response_data['otc']);
        
        \WOM\Logger::$Instance->info("Vouchers generated");

        $otc = $response_data['otc'];
        $password = $response_data['password'];
        return array($otc, $password);
    }

    private function VoucherCreate($vouchers, $nonce = null, $password = null) {
        if(!$nonce) {
            // Generate a unique nonce if there is none
            $nonce = Uuid::uuid4()->toString();
        }

        $payload = json_encode(array(
            'sourceId' => $this->id,
            'nonce' => $nonce,
            'password' => $password,
            'vouchers' => $vouchers
        ));

        $encryptedPayload = CryptoHelper::Encrypt($payload, $this->registry->publicKey);

        $jsonResponse = $this->registry->VoucherCreate($this->id, $nonce, base64_encode($encryptedPayload));

        $response = CryptoHelper::Decrypt($jsonResponse['payload'], $this->privKey);

        return json_decode($response, true);
    }

    private function VoucherVerify($otc) {
        $encryptedOtc = CryptoHelper::Encrypt(json_encode(array(
            'otc' =>$otc
        )), $this->registry->publicKey);

        $this->registry->VoucherVerify(base64_encode($encryptedOtc));
    }

}
