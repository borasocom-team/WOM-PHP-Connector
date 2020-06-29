<?php

require __DIR__ . '/vendor/autoload.php';

DEFINE("REGISTRY_PUBLIC_KEY", "keys/registry.pub");
DEFINE("INSTRUMENT_PRIVATE_KEY", "keys/instrument1.pem");
DEFINE("INSTRUMENT_PRIVATE_KEY_PASSWORD", "");
DEFINE("INSTRUMENT_ID", "5e74203f5f21bb265a2d26bd");

DEFINE("DEV", True);


date_default_timezone_set("UTC");

//
function random_float($min, $max) {
    return random_int($min, $max - 1) + (random_int(0, PHP_INT_MAX - 1) / PHP_INT_MAX );
}

// VOUCHER GENERATIN
// Generate 10 random Vouchers
$vouchers = array();
for($i=0; $i < 10; $i++){
    $vouchers[] = \WOM\Voucher::Create('H', random_float(40,52), random_float(10,15), new DateTime('NOW'));
}

// or, if they have identical aim, coordinates, and timestamp, you can generate them using the $count optional parameter
$vouchers[] = \WOM\Voucher::Create('H', random_float(40,52), random_float(10,15), new DateTime('NOW'), 10);


// INSTRUMENT CREATION
// Instantiate the Instrument with its ID, Private Key, and (optionally) the private key's password

$Instrument = new \WOM\Instrument(REGISTRY_PUBLIC_KEY, INSTRUMENT_ID, INSTRUMENT_PRIVATE_KEY, INSTRUMENT_PRIVATE_KEY_PASSWORD);

// Request Vouchers
$otc = null;
$password = null;

try{
    $Instrument->RequestVouchers($vouchers,  "", $password, $otc);
    echo "Otc: {$otc} Password:{$password}";
}catch(Exception $exception) {

    echo "No voucher generated :(";
}

