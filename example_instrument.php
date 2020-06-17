<?php

require __DIR__ . '/vendor/autoload.php';

DEFINE("CLIENT_PUBLIC_KEY", "keys/registry.pub");
DEFINE("CLIENT_PRIVATE_KEY", "keys/instrument1.pem");
DEFINE("CLIENT_PRIVATE_KEY_PASSWORD", "");
DEFINE("CLIENT_ID", "5e74203f5f21bb265a2d26bd");

date_default_timezone_set("UTC");

function random_float($min, $max) {
    return random_int($min, $max - 1) + (random_int(0, PHP_INT_MAX - 1) / PHP_INT_MAX );
}

$Instrument = new \WOM\Instrument(CLIENT_ID, CLIENT_PUBLIC_KEY, CLIENT_PRIVATE_KEY, CLIENT_PRIVATE_KEY_PASSWORD);

// Generate Vouchers
$vouchers = array();
for($i=0; $i < 10; $i++){
    $vouchers[] = \WOM\Voucher::Create('H', random_float(40,52), random_float(10,15), new DateTime('NOW'));
}

// Request Vouchers
$otc = null;
$password = null;


try{
    $Instrument->RequestVouchers($vouchers,  "", $password, $otc);
    echo "Otc: {$otc} Password:{$password}";
}catch(Exception $exception) {

    echo "No voucher generated :(";
}

