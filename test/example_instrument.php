<?php
require __DIR__ . '/vendor/autoload.php';

DEFINE("INSTRUMENT_PRIVATE_KEY", "/keys/instrument1.pem");
DEFINE("INSTRUMENT_PRIVATE_KEY_PASSWORD", "");
DEFINE("INSTRUMENT_ID", "5e74203f5f21bb265a2d26bd");

// Set development domain
\WOM\Config\Domain::SetDomain('dev.wom.social');
\WOM\Logger::Initialize(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::ERROR));

date_default_timezone_set("UTC");

function random_float($min, $max) {
    return random_int($min, $max - 1) + (random_int(0, PHP_INT_MAX - 1) / PHP_INT_MAX );
}

// VOUCHER GENERATION
// Generate 10 random Vouchers
$vouchers = array();
for($i=0; $i < 10; $i++) {
    $vouchers[] = \WOM\Voucher::Create('H', random_float(40, 52), random_float(10, 15), new DateTime('NOW'));
}

// or, if they have identical aim, coordinates, and timestamp, you can generate them using the $count optional parameter
$vouchers[] = \WOM\Voucher::Create('H', random_float(40, 52), random_float(10, 15), new DateTime('NOW'), 10);

// INSTRUMENT CREATION
// Instantiate the Instrument with its ID, Private Key, and (optionally) the private key's password

$instrument = new \WOM\Instrument(INSTRUMENT_ID, INSTRUMENT_PRIVATE_KEY, INSTRUMENT_PRIVATE_KEY_PASSWORD);

echo "Instrument created" . PHP_EOL;

// Request Vouchers
try{
    echo "Performing voucher request with " . count($vouchers) . " specifications" . PHP_EOL;
    $values = $instrument->RequestVouchers($vouchers);
    echo "Otc: {$values['otc']} Password: {$values['password']}" . PHP_EOL;
}
catch(Exception $exception) {
    echo "No vouchers generated :(" . PHP_EOL;
}
