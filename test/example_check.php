<?php
require __DIR__ . '/vendor/autoload.php';

DEFINE("POS_PRIVATE_KEY", "/keys/pos1.pem");
DEFINE("POS_ID", "5e74205c5f21bb265a2d26d8");

// Set development domain
\WOM\Config\Domain::SetDomain('dev.wom.social');
\WOM\Logger::Initialize(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::DEBUG));

date_default_timezone_set("UTC");

$POS = new \WOM\POS(POS_ID, POS_PRIVATE_KEY, '');

try {
    echo "Checking for previous payment request" . PHP_EOL;

    $status = $POS->GetPaymentStatus('f3d8824d-0aca-4139-81b2-680b2c90f429');

    echo "Payment has been performed: " . ($status['hasBeenPerformed'] ? 'yes' : 'no') . PHP_EOL;

    print_r($status);
}
catch(Exception $exception) {
    var_dump($exception->getTraceAsString());
    echo "Failed to perform check :(" . PHP_EOL;
}
