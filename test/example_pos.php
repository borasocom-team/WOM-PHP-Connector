<?php
require __DIR__ . '/vendor/autoload.php';

DEFINE("POS_PRIVATE_KEY", "/keys/pos1.pem");
DEFINE("POS_PRIVATE_KEY_PASSWORD", "");
DEFINE("POS_ID", "5e74205c5f21bb265a2d26d8");

// Set development domain
\WOM\Config\Domain::SetDomain('dev.wom.social');
\WOM\Logger::Initialize(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::DEBUG));

date_default_timezone_set("UTC");

$POS = new \WOM\POS(POS_ID, POS_PRIVATE_KEY, POS_PRIVATE_KEY_PASSWORD);

// Accept health WOM vouchers from Central Europe, not older than 14 days
$filter = \WOM\Filter::Create('H', array(52, -4), array(27, 35), 14);

// Accept all WOM vouchers, with an empty filter
//$filter = \WOM\Filter::Create();

try {
    echo "Creating payment request" . PHP_EOL;

    $values = $POS->RequestPayment(
        1,
        'https://example.org',
        $filter
    );

    echo "Payment request created (OTC: {$values[0]} Pwd: {$values[1]})" . PHP_EOL;

    $status = $POS->GetPaymentStatus($values[0]);

    echo "Payment has been performed: " . ($status['hasBeenPerformed'] ? 'yes' : 'no') . PHP_EOL;
}
catch(Exception $exception) {
    echo "No payment generated :(" . PHP_EOL;
}
